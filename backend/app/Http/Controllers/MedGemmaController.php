<?php

namespace App\Http\Controllers;

use App\Models\ImagingStudy;
use App\Models\Patient;
use App\Services\MedGemmaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedGemmaController extends Controller
{
    protected MedGemmaService $medGemmaService;

    public function __construct(MedGemmaService $medGemmaService)
    {
        $this->medGemmaService = $medGemmaService;
    }

    /**
     * Get MedGemma server status
     */
    public function status(): JsonResponse
    {
        try {
            $status = $this->medGemmaService->getServerStatus();
            $isEnabled = $this->medGemmaService->isEnabled();

            return response()->json([
                'enabled' => $isEnabled,
                'server_status' => $status,
                'capabilities' => [
                    'imaging_analysis' => true,
                    'lab_analysis' => true,
                    'second_opinion' => true,
                    'text_analysis' => true
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'enabled' => false,
                'server_status' => [],
                'error' => $e->getMessage(),
                'capabilities' => []
            ], 500);
        }
    }

    public function analyzeImagingStudy(Request $request, ImagingStudy $study): JsonResponse
    {
        try {
            $result = $this->medGemmaService->analyzeImagingStudy($study);

            // Check if the analysis was successful or failed due to validation
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'study_id' => $study->id,
                    'error' => $result['error'] ?? 'Analysis failed',
                    'error_type' => $result['error_type'] ?? 'unknown',
                    'impression' => $result['impression'] ?? 'Analysis unavailable',
                    'findings' => $result['findings'] ?? [],
                    'recommendations' => $result['recommendations'] ?? [],
                    'confidence' => $result['confidence'] ?? 0,
                    'note' => $result['note'] ?? null
                ], 400); // Bad Request for validation errors
            }

            return response()->json([
                'success' => true,
                'study_id' => $study->id,
                'impression' => $result['impression'] ?? $result['analysis'] ?? 'Analysis completed',
                'findings' => $result['findings'] ?? [],
                'recommendations' => $result['recommendations'] ?? [],
                'confidence' => $result['confidence'] ?? null,
                'note' => $result['note'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function analyzeLabs(Request $request, Patient $patient): JsonResponse
    {
        try {
            $result = $this->medGemmaService->analyzeLabs($patient);

            return response()->json([
                'success' => true,
                'patient_id' => $patient->id,
                'lab_comments' => $result['lab_comments'] ?? [$result['analysis'] ?? 'Lab analysis completed'],
                'recommendations' => $result['recommendations'] ?? [],
                'confidence' => $result['confidence'] ?? null,
                'note' => $result['note'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function combinedSecondOpinion(Request $request, Patient $patient): JsonResponse
    {
        try {
            $result = $this->medGemmaService->combinedSecondOpinion($patient);

            return response()->json([
                'success' => true,
                'patient_id' => $patient->id,
                'imaging' => $result['imaging'] ?? [$result['analysis'] ?? 'Second opinion analysis completed'],
                'labs' => $result['labs'] ?? [],
                'medications' => $result['medications'] ?? [],
                'analysis' => $result['analysis'] ?? 'Comprehensive analysis completed',
                'recommendations' => $result['recommendations'] ?? [],
                'confidence' => $result['confidence'] ?? null,
                'note' => $result['note'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze arbitrary medical text
     */
    public function analyzeText(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string|max:5000',
            'context' => 'sometimes|string|in:medical,clinical,diagnostic',
            'task' => 'sometimes|string|in:analysis,diagnosis,recommendation'
        ]);

        try {
            if (!$this->medGemmaService->isEnabled()) {
                return response()->json([
                    'success' => false,
                    'error' => 'MedGemma service not available. Please check server status.'
                ], 503);
            }

            $text = $request->input('text');
            $context = $request->input('context', 'medical');
            $task = $request->input('task', 'analysis');

            $result = $this->medGemmaService->analyzeText($text, $context, $task);

            return response()->json([
                'success' => true,
                'analysis' => $result['analysis'] ?? 'Analysis completed successfully',
                'recommendations' => $result['recommendations'] ?? [],
                'confidence' => $result['confidence'] ?? null,
                'note' => $result['note'] ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick diagnostic insights for a patient
     */
    public function quickInsights(Request $request, Patient $patient): JsonResponse
    {
        try {
            $insights = [];

            $insights['patient'] = [
                'age' => $patient->age ?? 'Unknown',
                'sex' => $patient->sex,
                'recent_studies' => $patient->imagingStudies()->count(),
                'recent_labs' => $patient->labOrders()->count()
            ];

            $recentStudies = $patient->imagingStudies()
                ->latest('performed_at')
                ->limit(3)
                ->get();

            $insights['recent_imaging'] = $recentStudies->map(function ($study) {
                return [
                    'id' => $study->id,
                    'modality' => $study->modality,
                    'description' => $study->description,
                    'performed_at' => $study->performed_at?->format('Y-m-d'),
                    'has_ai_analysis' => $study->aiResults()->exists()
                ];
            });

            $recentLabs = $patient->labOrders()
                ->where('status', 'resulted')
                ->with('test')
                ->latest()
                ->limit(5)
                ->get();

            $insights['recent_labs'] = $recentLabs->map(function ($lab) {
                return [
                    'test_name' => $lab->test->name ?? 'Unknown',
                    'result_value' => $lab->result_value,
                    'flag' => $lab->result_flag,
                    'date' => $lab->created_at->format('Y-m-d')
                ];
            });

            $insights['ai_status'] = [
                'medgemma_enabled' => $this->medGemmaService->isEnabled(),
                'server_running' => $this->medGemmaService->isServerRunning(),
                'available_analyses' => ['imaging', 'labs', 'second_opinion']
            ];

            return response()->json([
                'success' => true,
                'patient_id' => $patient->id,
                'insights' => $insights
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
