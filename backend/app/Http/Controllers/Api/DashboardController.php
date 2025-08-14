<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\ImagingStudy;
use App\Models\LabOrder;
use App\Models\AiResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        try {
            $stats = [
                'patients' => Patient::count(),
                'studies' => ImagingStudy::count(),
                'labs' => LabOrder::count(),
                'ai' => AiResult::count(),
                'recent_patients' => Patient::orderBy('created_at', 'desc')->limit(5)->get(['id', 'first_name', 'last_name', 'mrn']),
                'recent_studies' => ImagingStudy::with('patient:id,first_name,last_name')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(['id', 'patient_id', 'description', 'modality', 'created_at']),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load dashboard stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system health status
     */
    public function health()
    {
        try {
            $health = [
                'database' => 'connected',
                'server' => 'running',
                'medgemma' => app(\App\Services\MedGemmaService::class)->isEnabled() ? 'enabled' : 'disabled',
                'timestamp' => now()->toISOString()
            ];

            return response()->json($health);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Health check failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
