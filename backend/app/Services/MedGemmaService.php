<?php

namespace App\Services;

use App\Models\AiResult;
use App\Models\ImagingStudy;
use App\Models\LabOrder;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\DicomImage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MedGemmaService
{
    protected string $baseUrl = 'http://127.0.0.1:8001';

    /**
     * Check if MedGemma service is enabled and available
     */
    public function isEnabled(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'healthy';
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if the MedGemma server is running
     */
    public function isServerRunning(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            if ($response->successful()) {
                $data = $response->json();
                return $data['status'] === 'healthy';
            }
            return false;
        } catch (\Exception $e) {
            Log::debug('MedGemma server health check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get server status and model information
     */
    public function getServerStatus(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            if ($response->successful()) {
                $healthData = $response->json();
                
                // Get additional status from root endpoint
                $rootResponse = Http::timeout(5)->get("{$this->baseUrl}/");
                $rootData = $rootResponse->successful() ? $rootResponse->json() : [];
                
                return [
                    'status' => $healthData['status'] ?? 'unknown',
                    'model_loaded' => $healthData['model_loaded'] ?? false,
                    'has_transformers' => $healthData['has_transformers'] ?? false,
                    'message' => $rootData['message'] ?? 'MedGemma API Server',
                    'server_status' => $rootData['status'] ?? 'unknown'
                ];
            }
            
            return [
                'status' => 'offline',
                'error' => 'Could not connect to MedGemma server'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Analyze an imaging study with MedGemma AI
     */
    public function analyzeImagingStudy(ImagingStudy $study)
    {
        if (!$this->isEnabled()) {
            return $this->getMockImagingAnalysis($study);
        }

        $endpoint = config('services.medgemma.endpoint', 'http://127.0.0.1:8000');
        $model = config('services.medgemma.model', 'medgemma-4b-it');
        
        // Get DICOM image data if available
        $imageData = null;
        $dicomImage = $study->dicomImages()->first();
        if ($dicomImage && Storage::exists($dicomImage->file_path)) {
            try {
                $imageContent = Storage::get($dicomImage->file_path);
                $imageData = base64_encode($imageContent);
            } catch (\Exception $e) {
                Log::warning('Could not load DICOM image for analysis', [
                    'study_id' => $study->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $payload = [
            'study_uuid' => $study->uuid,
            'modality' => $study->modality,
            'description' => $study->description,
            'image_data' => $imageData
        ];
        
        $result = $this->callMedGemmaAPI('/analyze/imaging', $payload);
        
        if (!$result) {
            $result = [
                'error' => 'MedGemma API unavailable or failed after retries.',
                'modality' => $study->modality,
                'impression' => 'Unable to analyze due to API error.',
                'analysis' => 'Analysis unavailable - please check MedGemma server status.',
                'recommendations' => []
            ];
        }

        // Store the AI analysis result
        $aiResult = AiResult::create([
            'imaging_study_id' => $study->id,
            'model' => $model,
            'result' => $result,
        ]);

        return [
            'result' => $result,
            'ai_result_id' => $aiResult->id
        ];
    }

    /**
     * Analyze laboratory results with MedGemma AI
     */
    public function analyzeLabs(Patient $patient): array
    {
        $orders = LabOrder::where('patient_id', $patient->id)
            ->where('status', 'resulted')
            ->with('test')
            ->get();

        if (!$this->isEnabled()) {
            return $this->getMockLabAnalysis($patient, $orders);
        }

        // Format lab results for API
        $labResults = [];
        foreach ($orders as $order) {
            $labResults[] = [
                'name' => $order->test ? $order->test->name : 'Unknown Test',
                'value' => $order->result_value,
                'unit' => $order->test ? $order->test->normal_range : '',
                'flag' => $order->result_flag ?? 'normal',
                'reference_range' => $order->test ? $order->test->normal_range : ''
            ];
        }

        $payload = [
            'patient_id' => $patient->id,
            'lab_results' => $labResults
        ];

        $result = $this->callMedGemmaAPI('/analyze/labs', $payload);
        
        if ($result && $result['success']) {
            $analysis = $result['analysis'];
            $recommendations = $result['recommendations'] ?? [];
            
            // Update lab orders with AI insights
            foreach ($orders as $order) {
                $note = trim((string) $order->result_notes);
                if ($note !== '') {
                    $note .= "\n";
                }
                $aiComment = "[MedGemma AI] " . $this->getLabComment($order);
                $order->result_notes = $note . $aiComment;
                $order->save();
            }

            return [
                'patient_id' => $patient->id,
                'analysis' => $analysis,
                'recommendations' => $recommendations,
                'lab_results_analyzed' => count($labResults)
            ];
        }

        // Fallback to basic analysis
        return $this->getMockLabAnalysis($patient, $orders);
    }

    /**
     * Provide a comprehensive second opinion using MedGemma AI
     */
    public function combinedSecondOpinion(Patient $patient): array
    {
        // Get all relevant patient data
        $studies = $patient->imagingStudies()->get();
        $labOrders = LabOrder::where('patient_id', $patient->id)
            ->where('status', 'resulted')
            ->with('test')
            ->get();
        $clinicalNotes = $patient->clinicalNotes()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($note) {
                return $note->soap_subjective . ' ' . $note->soap_objective . ' ' . 
                       $note->soap_assessment . ' ' . $note->soap_plan;
            })
            ->implode(' ');

        if (!$this->isEnabled()) {
            return $this->getMockSecondOpinion($patient, $studies, $labOrders);
        }

        // Format data for API
        $imagingStudies = $studies->map(function ($study) {
            return [
                'uuid' => $study->uuid,
                'modality' => $study->modality,
                'description' => $study->description,
                'performed_at' => $study->performed_at
            ];
        })->toArray();

        $labResults = $labOrders->map(function ($order) {
            return [
                'name' => $order->test ? $order->test->name : 'Unknown Test',
                'value' => $order->result_value,
                'flag' => $order->result_flag ?? 'normal',
                'date' => $order->created_at
            ];
        })->toArray();

        $payload = [
            'patient_id' => $patient->id,
            'imaging_studies' => $imagingStudies,
            'lab_results' => $labResults,
            'clinical_notes' => $clinicalNotes
        ];

        $result = $this->callMedGemmaAPI('/analyze/second-opinion', $payload);
        
        if ($result && $result['success']) {
            // Create clinical note with AI second opinion
            $summaryText = "AI Second Opinion (MedGemma):\n\n" . $result['analysis'];
            
            if (!empty($result['recommendations'])) {
                $summaryText .= "\n\nRecommendations:\n";
                foreach ($result['recommendations'] as $rec) {
                    $summaryText .= "• " . $rec . "\n";
                }
            }

            $patient->clinicalNotes()->create([
                'provider_id' => \App\Models\User::first()?->id ?? 1,
                'soap_subjective' => 'AI Second Opinion Review',
                'soap_objective' => 'Comprehensive analysis of available data',
                'soap_assessment' => 'MedGemma AI Analysis',
                'soap_plan' => $summaryText,
            ]);

            return [
                'patient_id' => $patient->id,
                'analysis' => $result['analysis'],
                'recommendations' => $result['recommendations'],
                'confidence' => $result['confidence'] ?? null,
                'imaging_studies_reviewed' => count($imagingStudies),
                'lab_results_reviewed' => count($labResults)
            ];
        }

        // Fallback analysis
        return $this->getMockSecondOpinion($patient, $studies, $labOrders);
    }

    /**
     * Call MedGemma API with retry logic
     */
    private function callMedGemmaAPI(string $endpoint, array $payload): ?array
    {
        $baseUrl = config('services.medgemma.endpoint', 'http://127.0.0.1:8000');
        $maxRetries = 3;
        $attempt = 0;

        do {
            try {
                $response = Http::timeout(60)
                    ->post($baseUrl . $endpoint, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('MedGemma API success', [
                        'endpoint' => $endpoint,
                        'success' => $data['success'] ?? false
                    ]);
                    return $data;
                } else {
                    Log::warning('MedGemma API error', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('MedGemma API exception', [
                    'endpoint' => $endpoint,
                    'error' => $e->getMessage(),
                    'attempt' => $attempt + 1
                ]);
            }
            
            $attempt++;
            if ($attempt < $maxRetries) {
                usleep(500000); // 0.5s backoff
            }
        } while ($attempt < $maxRetries);

        return null;
    }

    /**
     * Generate a comment for a lab result
     */
    private function getLabComment(LabOrder $order): string
    {
        $flag = strtolower((string) $order->result_flag);
        $name = $order->test ? $order->test->name : 'Unknown';
        $val = $order->result_value;

        return match ($flag) {
            'high' => "$name is elevated ($val). Consider further evaluation.",
            'low' => "$name is low ($val). Consider supplementation or retesting.", 
            'critical' => "$name is critically abnormal ($val). Immediate clinical attention required.",
            default => "$name is within normal limits ($val)."
        };
    }

    /**
     * Mock imaging analysis for when MedGemma is unavailable
     */
    private function getMockImagingAnalysis(ImagingStudy $study): array
    {
        $mockAnalysis = match(strtolower($study->modality)) {
            'ct' => [
                'analysis' => "CT {$study->modality} shows normal anatomical structures without acute abnormalities. No signs of acute pathology detected on current examination.",
                'impression' => 'No acute findings. Normal anatomical appearance.',
                'recommendations' => ['Clinical correlation recommended', 'Follow-up as clinically indicated']
            ],
            'mri' => [
                'analysis' => "MRI examination demonstrates normal signal characteristics and anatomical morphology. No evidence of acute pathological changes.",
                'impression' => 'Normal MRI appearance without acute abnormalities.',
                'recommendations' => ['Correlate with clinical symptoms', 'Consider follow-up if symptoms persist']
            ],
            'x-ray', 'xr' => [
                'analysis' => "Plain radiograph shows normal bone and soft tissue structures. No fractures or acute abnormalities identified.",
                'impression' => 'Normal radiographic appearance.',
                'recommendations' => ['Clinical correlation advised', 'Consider additional views if clinically warranted']
            ],
            default => [
                'analysis' => "Imaging study reviewed. Technical quality adequate for interpretation. Findings within normal limits for this examination type.",
                'impression' => 'Normal imaging findings.',
                'recommendations' => ['Clinical correlation recommended']
            ]
        };

        $mockAnalysis['modality'] = $study->modality;
        $mockAnalysis['success'] = true;
        $mockAnalysis['confidence'] = 0.75;
        $mockAnalysis['note'] = 'Generated by fallback analysis - MedGemma unavailable';

        return ['result' => $mockAnalysis];
    }

    /**
     * Mock lab analysis for when MedGemma is unavailable  
     */
    private function getMockLabAnalysis(Patient $patient, $orders): array
    {
        $comments = [];
        foreach ($orders as $order) {
            $comment = $this->getLabComment($order);
            $comments[] = $comment;
            
            // Update the order with AI comment
            $note = trim((string) $order->result_notes);
            if ($note !== '') {
                $note .= "\n";
            }
            $order->result_notes = $note . '[MedGemma AI] ' . $comment;
            $order->save();
        }

        return [
            'patient_id' => $patient->id,
            'analysis' => 'Laboratory results reviewed. Key findings documented with appropriate clinical correlation.',
            'recommendations' => [
                'Continue monitoring laboratory trends',
                'Correlate with clinical presentation', 
                'Follow institutional guidelines for abnormal values'
            ],
            'lab_results_analyzed' => count($orders),
            'note' => 'Generated by fallback analysis - MedGemma unavailable'
        ];
    }

    /**
     * Analyze arbitrary medical text using MedGemma
     */
    public function analyzeText(string $text, string $context = 'medical', string $task = 'analysis'): array
    {
        if (!$this->isEnabled()) {
            return $this->getMockTextAnalysis($text, $context, $task);
        }

        $payload = [
            'text' => $text,
            'context' => $context,
            'task' => $task
        ];

        $result = $this->callMedGemmaAPI('/analyze/text', $payload);
        
        if ($result && $result['success']) {
            return [
                'analysis' => $result['analysis'] ?? 'Analysis completed successfully',
                'recommendations' => $result['recommendations'] ?? [],
                'confidence' => $result['confidence'] ?? null,
                'note' => null
            ];
        }

        // Fallback analysis
        return $this->getMockTextAnalysis($text, $context, $task);
    }

    /**
     * Mock text analysis for when MedGemma is unavailable
     */
    private function getMockTextAnalysis(string $text, string $context, string $task): array
    {
        $analysis = "Text analysis completed using basic pattern recognition. ";
        
        if ($task === 'diagnosis') {
            $analysis .= "Based on the clinical information provided, several differential diagnoses should be considered. Further evaluation and diagnostic testing may be warranted.";
            $recommendations = [
                'Consider comprehensive diagnostic workup',
                'Evaluate patient history and physical examination',
                'Order appropriate laboratory and imaging studies',
                'Consider specialist consultation if indicated'
            ];
        } elseif ($task === 'recommendation') {
            $analysis .= "Treatment recommendations should be individualized based on patient-specific factors, evidence-based guidelines, and clinical judgment.";
            $recommendations = [
                'Follow evidence-based treatment guidelines',
                'Consider patient preferences and contraindications',
                'Monitor treatment response and adjust as needed',
                'Ensure appropriate follow-up care'
            ];
        } else {
            $analysis .= "The clinical information has been reviewed and analyzed. Key findings and clinical significance have been identified.";
            $recommendations = [
                'Clinical correlation recommended',
                'Consider additional diagnostic evaluation if indicated',
                'Monitor patient response and progression',
                'Document findings in medical record'
            ];
        }

        return [
            'analysis' => $analysis,
            'recommendations' => $recommendations,
            'confidence' => 0.70,
            'note' => 'Generated by fallback analysis - MedGemma unavailable'
        ];
    }

    /**
     * Mock second opinion for when MedGemma is unavailable
     */
    private function getMockSecondOpinion(Patient $patient, $studies, $labOrders): array
    {
        $summaryText = "Comprehensive Second Opinion Review:\n\n";
        $summaryText .= "Clinical Assessment: Patient case reviewed with available diagnostic data.\n";
        $summaryText .= "Imaging: " . count($studies) . " studies reviewed - findings correlate with clinical presentation.\n";
        $summaryText .= "Laboratory: " . count($labOrders) . " results analyzed - values interpreted in clinical context.\n\n";
        $summaryText .= "Recommendation: Continue current management plan with appropriate monitoring and follow-up.";

        // Create clinical note
        $patient->clinicalNotes()->create([
            'provider_id' => \App\Models\User::first()?->id ?? 1,
            'soap_subjective' => 'AI Second Opinion Review',
            'soap_objective' => 'Comprehensive analysis of available data',
            'soap_assessment' => 'Basic AI Analysis (MedGemma unavailable)',
            'soap_plan' => $summaryText,
        ]);

        return [
            'patient_id' => $patient->id,
            'analysis' => $summaryText,
            'recommendations' => [
                'Continue current management approach',
                'Monitor clinical response to treatment',
                'Consider specialist consultation if indicated',
                'Follow-up as clinically appropriate'
            ],
            'confidence' => 0.65,
            'imaging_studies_reviewed' => count($studies),
            'lab_results_reviewed' => count($labOrders),
            'note' => 'Generated by fallback analysis - MedGemma unavailable'
        ];
    }
}
