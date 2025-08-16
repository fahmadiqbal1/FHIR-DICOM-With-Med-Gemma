<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabEquipment;
use App\Models\LabOrder;
use App\Models\LabResult;
use App\Services\EquipmentIntegrationService;
use App\Services\OCRResultService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LabEquipmentController extends Controller
{
    protected EquipmentIntegrationService $equipmentService;
    protected OCRResultService $ocrService;

    public function __construct(
        EquipmentIntegrationService $equipmentService,
        OCRResultService $ocrService
    ) {
        $this->equipmentService = $equipmentService;
        $this->ocrService = $ocrService;
    }

    public function index(): JsonResponse
    {
        $equipment = LabEquipment::with(['results' => function($query) {
            $query->latest()->limit(5);
        }])->get();

        return response()->json([
            'equipment' => $equipment->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'model' => $item->model,
                    'manufacturer' => $item->manufacturer,
                    'is_active' => $item->is_active,
                    'is_online' => $item->isOnline(),
                    'last_connected_at' => $item->last_connected_at,
                    'supported_tests' => $item->supported_tests,
                    'recent_results_count' => $item->results->count()
                ];
            })
        ]);
    }

    public function show(LabEquipment $equipment): JsonResponse
    {
        $equipment->load(['results' => function($query) {
            $query->with(['labOrder.patient', 'labOrder.test'])->latest();
        }]);

        return response()->json([
            'equipment' => [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'model' => $equipment->model,
                'manufacturer' => $equipment->manufacturer,
                'serial_number' => $equipment->serial_number,
                'connection_type' => $equipment->connection_type,
                'is_active' => $equipment->is_active,
                'is_online' => $equipment->isOnline(),
                'last_connected_at' => $equipment->last_connected_at,
                'supported_tests' => $equipment->supported_tests,
                'configuration' => $equipment->configuration,
                'results' => $equipment->results->map(function($result) {
                    return [
                        'id' => $result->id,
                        'test_name' => $result->test_name,
                        'result_value' => $result->result_value,
                        'result_units' => $result->result_units,
                        'result_flag' => $result->result_flag,
                        'performed_at' => $result->performed_at,
                        'patient_name' => $result->labOrder?->patient?->name,
                        'source_type' => $result->source_type,
                        'quality_control_passed' => $result->quality_control_passed
                    ];
                })
            ]
        ]);
    }

    public function fetchResults(Request $request): JsonResponse
    {
        try {
            $equipmentId = $request->input('equipment_id');
            
            if ($equipmentId) {
                $equipment = LabEquipment::findOrFail($equipmentId);
                $results = $this->equipmentService->fetchResultsFromEquipment($equipment);
                
                return response()->json([
                    'success' => true,
                    'equipment' => $equipment->name,
                    'results' => $results,
                    'message' => "Successfully fetched results from {$equipment->name}"
                ]);
            } else {
                $results = $this->equipmentService->fetchResultsFromAllEquipment();
                
                return response()->json([
                    'success' => true,
                    'results' => $results,
                    'message' => 'Successfully fetched results from all active equipment'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadResultImage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
                'lab_order_id' => 'required|exists:lab_orders,id',
                'equipment_id' => 'nullable|exists:lab_equipment,id'
            ]);

            $result = $this->ocrService->processLabResultImage(
                $request->file('image'),
                $request->input('lab_order_id'),
                $request->input('equipment_id')
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lab result image processed successfully',
                    'results' => $result['results']->map(function($labResult) {
                        return [
                            'id' => $labResult->id,
                            'test_name' => $labResult->test_name,
                            'result_value' => $labResult->result_value,
                            'result_units' => $labResult->result_units,
                            'result_flag' => $labResult->result_flag,
                            'confidence' => $labResult->ocr_confidence,
                            'needs_verification' => $labResult->result_status === 'needs_verification'
                        ];
                    }),
                    'ocr_confidence' => $result['ocr_confidence'],
                    'extracted_text' => $result['extracted_text']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 422);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyResult(Request $request, LabResult $result): JsonResponse
    {
        try {
            $request->validate([
                'verified' => 'required|boolean',
                'corrected_value' => 'nullable|string',
                'notes' => 'nullable|string'
            ]);

            $result->update([
                'result_status' => $request->input('verified') ? 'final' : 'corrected',
                'result_value' => $request->input('corrected_value', $result->result_value),
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'notes' => $request->input('notes')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Result verification completed',
                'result' => [
                    'id' => $result->id,
                    'result_status' => $result->result_status,
                    'result_value' => $result->result_value,
                    'verified_at' => $result->verified_at,
                    'notes' => $result->notes
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getResults(Request $request): JsonResponse
    {
        $query = LabResult::with(['labOrder.patient', 'labOrder.test', 'equipment', 'verifier']);

        // Filter by equipment
        if ($request->has('equipment_id')) {
            $query->where('equipment_id', $request->input('equipment_id'));
        }

        // Filter by source type
        if ($request->has('source_type')) {
            $query->where('source_type', $request->input('source_type'));
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('result_status', $request->input('status'));
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('performed_at', '>=', $request->input('from_date'));
        }

        if ($request->has('to_date')) {
            $query->whereDate('performed_at', '<=', $request->input('to_date'));
        }

        $results = $query->orderBy('performed_at', 'desc')->paginate(20);

        return response()->json([
            'results' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'total' => $results->total()
            ]
        ]);
    }

    public function getStatistics(): JsonResponse
    {
        $stats = [
            'total_equipment' => LabEquipment::count(),
            'active_equipment' => LabEquipment::where('is_active', true)->count(),
            'online_equipment' => LabEquipment::where('is_active', true)
                ->where('last_connected_at', '>', now()->subMinutes(5))
                ->count(),
            'today_results' => LabResult::whereDate('performed_at', today())->count(),
            'auto_results' => LabResult::where('source_type', 'equipment')
                ->whereDate('performed_at', today())
                ->count(),
            'ocr_results' => LabResult::where('source_type', 'ocr')
                ->whereDate('performed_at', today())
                ->count(),
            'pending_verification' => LabResult::where('result_status', 'needs_verification')->count(),
            'equipment_performance' => LabEquipment::with(['results' => function($query) {
                $query->whereDate('performed_at', today());
            }])->get()->map(function($equipment) {
                return [
                    'name' => $equipment->name,
                    'today_results' => $equipment->results->count(),
                    'is_online' => $equipment->isOnline(),
                    'last_connected' => $equipment->last_connected_at
                ];
            })
        ];

        return response()->json($stats);
    }

    public function testConnection(LabEquipment $equipment): JsonResponse
    {
        try {
            // Test connection to the equipment
            $results = $this->equipmentService->fetchResultsFromEquipment($equipment);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully connected to {$equipment->name}",
                'is_online' => $equipment->isOnline(),
                'last_connected_at' => $equipment->last_connected_at,
                'test_results_count' => count($results)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'is_online' => false
            ], 500);
        }
    }
}
