<?php

namespace App\Services;

use App\Models\LabOrder;
use App\Models\LabResult;
use App\Models\LabEquipment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Http;

class OCRResultService
{
    protected ?string $ocrApiKey;
    protected ?string $ocrEndpoint;

    public function __construct()
    {
        $this->ocrApiKey = config('services.ocr.api_key', env('OCR_API_KEY'));
        $this->ocrEndpoint = config('services.ocr.endpoint', env('OCR_ENDPOINT', 'https://api.ocr.space/parse/image'));
    }

    public function processLabResultImage(UploadedFile $image, int $labOrderId, ?int $equipmentId = null): array
    {
        try {
            // Store the image
            $imagePath = $this->storeImage($image);
            
            // Extract text using OCR
            $ocrResult = $this->extractTextFromImage($imagePath);
            
            // Parse the extracted text for lab results
            $parsedResults = $this->parseLabResults($ocrResult['text'], $ocrResult['confidence']);
            
            // Create lab result records
            $labResults = $this->createLabResults($parsedResults, $labOrderId, $equipmentId, $imagePath, $ocrResult['confidence']);
            
            return [
                'success' => true,
                'results' => $labResults,
                'ocr_confidence' => $ocrResult['confidence'],
                'extracted_text' => $ocrResult['text'],
                'image_path' => $imagePath
            ];
            
        } catch (Exception $e) {
            Log::error('OCR processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function storeImage(UploadedFile $image): string
    {
        $filename = 'lab_results/' . date('Y/m/d') . '/' . Str::uuid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public', $filename);
        return $filename;
    }

    protected function extractTextFromImage(string $imagePath): array
    {
        $fullPath = Storage::path('public/' . $imagePath);
        
        // Try multiple OCR services for better accuracy
        $ocrResult = $this->tryOCRSpaceAPI($fullPath);
        
        if (!$ocrResult['success'] || $ocrResult['confidence'] < 0.7) {
            // Fallback to Tesseract if available
            $tesseractResult = $this->tryTesseractOCR($fullPath);
            if ($tesseractResult['success'] && $tesseractResult['confidence'] > $ocrResult['confidence']) {
                $ocrResult = $tesseractResult;
            }
        }

        return $ocrResult;
    }

    protected function tryOCRSpaceAPI(string $imagePath): array
    {
        try {
            $response = Http::asMultipart()
                ->attach('file', file_get_contents($imagePath), basename($imagePath))
                ->post($this->ocrEndpoint, [
                    'apikey' => $this->ocrApiKey,
                    'language' => 'eng',
                    'detectOrientation' => 'true',
                    'scale' => 'true',
                    'OCREngine' => '2'
                ]);

            $result = $response->json();
            
            if (isset($result['ParsedResults'][0]['ParsedText'])) {
                return [
                    'success' => true,
                    'text' => $result['ParsedResults'][0]['ParsedText'],
                    'confidence' => $this->calculateConfidence($result['ParsedResults'][0])
                ];
            }

            return ['success' => false, 'text' => '', 'confidence' => 0];
            
        } catch (Exception $e) {
            Log::error('OCR Space API failed: ' . $e->getMessage());
            return ['success' => false, 'text' => '', 'confidence' => 0];
        }
    }

    protected function tryTesseractOCR(string $imagePath): array
    {
        try {
            // Check if Tesseract is available
            $tesseractPath = exec('which tesseract');
            if (!$tesseractPath) {
                return ['success' => false, 'text' => '', 'confidence' => 0];
            }

            // Run Tesseract with confidence output
            $outputFile = tempnam(sys_get_temp_dir(), 'tesseract');
            $command = "tesseract '{$imagePath}' '{$outputFile}' -c tessedit_create_tsv=1";
            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($outputFile . '.tsv')) {
                $tsvContent = file_get_contents($outputFile . '.tsv');
                $text = $this->extractTextFromTSV($tsvContent);
                $confidence = $this->calculateTesseractConfidence($tsvContent);
                
                unlink($outputFile . '.tsv');
                
                return [
                    'success' => true,
                    'text' => $text,
                    'confidence' => $confidence
                ];
            }

            return ['success' => false, 'text' => '', 'confidence' => 0];
            
        } catch (Exception $e) {
            Log::error('Tesseract OCR failed: ' . $e->getMessage());
            return ['success' => false, 'text' => '', 'confidence' => 0];
        }
    }

    protected function calculateConfidence(array $ocrResult): float
    {
        // OCR.space doesn't provide direct confidence, so we estimate based on text quality
        $text = $ocrResult['ParsedText'] ?? '';
        
        if (empty($text)) return 0;
        
        // Basic heuristics for confidence
        $score = 0.5; // Base score
        
        // Check for common lab result patterns
        if (preg_match('/\d+\.?\d*\s*(mg\/dl|mmol\/l|g\/dl|%|\/ul)/i', $text)) {
            $score += 0.3;
        }
        
        // Check for test names
        if (preg_match('/(hemoglobin|glucose|cholesterol|sodium|potassium|chloride)/i', $text)) {
            $score += 0.2;
        }
        
        return min(1.0, $score);
    }

    protected function extractTextFromTSV(string $tsvContent): string
    {
        $lines = explode("\n", $tsvContent);
        $text = '';
        
        foreach ($lines as $line) {
            $columns = explode("\t", $line);
            if (count($columns) >= 12 && !empty($columns[11])) {
                $text .= $columns[11] . ' ';
            }
        }
        
        return trim($text);
    }

    protected function calculateTesseractConfidence(string $tsvContent): float
    {
        $lines = explode("\n", $tsvContent);
        $confidences = [];
        
        foreach ($lines as $line) {
            $columns = explode("\t", $line);
            if (count($columns) >= 11 && is_numeric($columns[10])) {
                $confidences[] = (float)$columns[10];
            }
        }
        
        return empty($confidences) ? 0 : array_sum($confidences) / count($confidences) / 100;
    }

    protected function parseLabResults(string $text, float $confidence): array
    {
        $results = [];
        $lines = explode("\n", $text);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Parse different result formats
            $parsed = $this->parseHematologyResult($line) ??
                     $this->parseElectrolyteResult($line) ??
                     $this->parseBiochemistryResult($line) ??
                     $this->parseGenericResult($line);
            
            if ($parsed) {
                $parsed['confidence'] = $confidence;
                $results[] = $parsed;
            }
        }
        
        return $results;
    }

    protected function parseHematologyResult(string $line): ?array
    {
        // Patterns for hematology results
        $patterns = [
            '/^(WBC|RBC|HGB|HCT|PLT|Hemoglobin|Hematocrit)\s*:?\s*([0-9.,]+)\s*([a-zA-Z\/]+)?/i',
            '/^(White Blood Cells?|Red Blood Cells?|Platelets?)\s*:?\s*([0-9.,]+)\s*([a-zA-Z\/]+)?/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                return [
                    'test_name' => trim($matches[1]),
                    'test_code' => $this->normalizeTestCode($matches[1]),
                    'result_value' => str_replace(',', '', $matches[2]),
                    'units' => $matches[3] ?? '',
                    'reference_range' => $this->extractReferenceRange($line)
                ];
            }
        }
        
        return null;
    }

    protected function parseElectrolyteResult(string $line): ?array
    {
        // Patterns for electrolyte results
        $patterns = [
            '/^(Na\+?|K\+?|Cl-?|Sodium|Potassium|Chloride)\s*:?\s*([0-9.,]+)\s*(mmol\/L|mEq\/L)?/i',
            '/^(CO2|Bicarbonate|HCO3)\s*:?\s*([0-9.,]+)\s*(mmol\/L|mEq\/L)?/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                return [
                    'test_name' => trim($matches[1]),
                    'test_code' => $this->normalizeTestCode($matches[1]),
                    'result_value' => str_replace(',', '', $matches[2]),
                    'units' => $matches[3] ?? 'mmol/L',
                    'reference_range' => $this->extractReferenceRange($line)
                ];
            }
        }
        
        return null;
    }

    protected function parseBiochemistryResult(string $line): ?array
    {
        // Patterns for biochemistry results
        $patterns = [
            '/^(Glucose|GLU|BUN|Creatinine|CREA|ALT|AST|Bilirubin|BILI)\s*:?\s*([0-9.,]+)\s*([a-zA-Z\/]+)?/i',
            '/^(Total Protein|Albumin|Cholesterol|Triglycerides?)\s*:?\s*([0-9.,]+)\s*([a-zA-Z\/]+)?/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                return [
                    'test_name' => trim($matches[1]),
                    'test_code' => $this->normalizeTestCode($matches[1]),
                    'result_value' => str_replace(',', '', $matches[2]),
                    'units' => $matches[3] ?? 'mg/dL',
                    'reference_range' => $this->extractReferenceRange($line)
                ];
            }
        }
        
        return null;
    }

    protected function parseGenericResult(string $line): ?array
    {
        // Generic pattern for any test result
        if (preg_match('/^([A-Za-z][A-Za-z0-9\s\-]+)\s*:?\s*([0-9.,]+)\s*([a-zA-Z\/\%]+)?/i', $line, $matches)) {
            $testName = trim($matches[1]);
            
            // Skip obvious non-medical terms
            $skipWords = ['date', 'time', 'patient', 'sample', 'report', 'lab', 'page'];
            if (in_array(strtolower($testName), $skipWords)) {
                return null;
            }
            
            return [
                'test_name' => $testName,
                'test_code' => $this->normalizeTestCode($testName),
                'result_value' => str_replace(',', '', $matches[2]),
                'units' => $matches[3] ?? '',
                'reference_range' => $this->extractReferenceRange($line)
            ];
        }
        
        return null;
    }

    protected function normalizeTestCode(string $testName): string
    {
        $codes = [
            'hemoglobin' => 'HGB',
            'hematocrit' => 'HCT',
            'white blood cells' => 'WBC',
            'red blood cells' => 'RBC',
            'platelets' => 'PLT',
            'sodium' => 'NA',
            'potassium' => 'K',
            'chloride' => 'CL',
            'glucose' => 'GLU',
            'creatinine' => 'CREA',
            'bilirubin' => 'BILI'
        ];
        
        $normalized = strtolower(trim($testName));
        return $codes[$normalized] ?? strtoupper(str_replace(' ', '', $testName));
    }

    protected function extractReferenceRange(string $line): ?string
    {
        // Look for reference ranges in parentheses or after keywords
        $patterns = [
            '/\(([0-9.,\-\s<>]+(?:mg\/dl|mmol\/l|g\/dl|%|\/ul)?)\)/i',
            '/(?:ref|reference|normal)\s*:?\s*([0-9.,\-\s<>]+(?:mg\/dl|mmol\/l|g\/dl|%|\/ul)?)/i',
            '/([0-9.,]+\s*-\s*[0-9.,]+\s*(?:mg\/dl|mmol\/l|g\/dl|%|\/ul)?)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                return trim($matches[1]);
            }
        }
        
        return null;
    }

    protected function createLabResults(array $parsedResults, int $labOrderId, ?int $equipmentId, string $imagePath, float $confidence): array
    {
        $labResults = [];
        $labOrder = LabOrder::find($labOrderId);
        
        if (!$labOrder) {
            throw new Exception("Lab order not found: {$labOrderId}");
        }
        
        foreach ($parsedResults as $result) {
            $labResult = LabResult::create([
                'lab_order_id' => $labOrderId,
                'equipment_id' => $equipmentId,
                'test_code' => $result['test_code'],
                'test_name' => $result['test_name'],
                'result_value' => $result['result_value'],
                'result_units' => $result['units'],
                'reference_range' => $result['reference_range'],
                'result_status' => $confidence > 0.8 ? 'preliminary' : 'needs_verification',
                'performed_at' => now(),
                'source_type' => 'ocr',
                'ocr_image_path' => $imagePath,
                'ocr_confidence' => $confidence,
                'quality_control_passed' => $confidence > 0.7,
                'notes' => $confidence < 0.8 ? 'Low OCR confidence - requires manual verification' : null
            ]);
            
            $labResults[] = $labResult;
        }
        
        // Update lab order status
        $labOrder->update([
            'status' => 'resulted',
            'resulted_at' => now()
        ]);
        
        return $labResults;
    }
}
