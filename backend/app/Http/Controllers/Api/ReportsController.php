<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiResult;
use App\Models\ImagingStudy;
use App\Models\LabOrder;
use App\Models\Patient;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Get all reports with filtering and pagination
     */
    public function index(Request $request)
    {
        try {
            $query = collect();
            
            // AI Results
            $aiReports = AiResult::with(['imagingStudy.patient'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($ai) {
                    $patient = $ai->imagingStudy->patient ?? null;
                    return [
                        'id' => $ai->id,
                        'type' => 'AI Analysis',
                        'patient_name' => $patient ? ($patient->first_name . ' ' . $patient->last_name) : 'Unknown',
                        'patient_id' => $patient->id ?? null,
                        'date' => $ai->created_at->format('Y-m-d H:i'),
                        'summary' => $ai->model . ' - Confidence: ' . ($ai->confidence_score ?? 'N/A'),
                        'details' => json_encode($ai->result, JSON_PRETTY_PRINT),
                        'study_id' => $ai->imaging_study_id,
                        'created_at' => $ai->created_at,
                    ];
                });

            // Lab Orders
            $labReports = LabOrder::with(['patient', 'test'])
                ->whereNotNull('result_value')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($lab) {
                    return [
                        'id' => $lab->id,
                        'type' => 'Lab Result',
                        'patient_name' => ($lab->patient->first_name ?? '') . ' ' . ($lab->patient->last_name ?? ''),
                        'patient_id' => $lab->patient_id,
                        'date' => $lab->created_at->format('Y-m-d H:i'),
                        'summary' => ($lab->test->name ?? 'Lab Test') . ': ' . $lab->result_value . ' ' . ($lab->result_flag ?? ''),
                        'details' => json_encode([
                            'test' => $lab->test->name ?? 'Unknown',
                            'value' => $lab->result_value,
                            'flag' => $lab->result_flag,
                            'notes' => $lab->result_notes,
                            'status' => $lab->status,
                        ], JSON_PRETTY_PRINT),
                        'lab_id' => $lab->id,
                        'created_at' => $lab->created_at,
                    ];
                });

            // Imaging Studies
            $imagingReports = ImagingStudy::with('patient')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($study) {
                    return [
                        'id' => $study->id,
                        'type' => 'Imaging Study',
                        'patient_name' => ($study->patient->first_name ?? '') . ' ' . ($study->patient->last_name ?? ''),
                        'patient_id' => $study->patient_id,
                        'date' => $study->created_at->format('Y-m-d H:i'),
                        'summary' => $study->modality . ' - ' . $study->description,
                        'details' => json_encode([
                            'modality' => $study->modality,
                            'description' => $study->description,
                            'status' => $study->status,
                            'started_at' => $study->started_at,
                        ], JSON_PRETTY_PRINT),
                        'study_id' => $study->id,
                        'created_at' => $study->created_at,
                    ];
                });

            // Combine all reports
            $allReports = $aiReports->concat($labReports)->concat($imagingReports)
                ->sortByDesc('created_at')
                ->values();

            // Apply filters if provided
            if ($request->has('type')) {
                $allReports = $allReports->where('type', $request->type);
            }

            if ($request->has('patient_id')) {
                $allReports = $allReports->where('patient_id', $request->patient_id);
            }

            // Paginate results
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 50);
            $offset = ($page - 1) * $perPage;
            
            $paginatedReports = $allReports->slice($offset, $perPage)->values();

            return response()->json([
                'data' => $paginatedReports,
                'total' => $allReports->count(),
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($allReports->count() / $perPage)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load reports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific report by ID and type
     */
    public function show(Request $request, $id)
    {
        try {
            $type = $request->get('type');
            
            switch ($type) {
                case 'AI Analysis':
                    $report = AiResult::with(['imagingStudy.patient'])->findOrFail($id);
                    break;
                case 'Lab Result':
                    $report = LabOrder::with(['patient', 'test'])->findOrFail($id);
                    break;
                case 'Imaging Study':
                    $report = ImagingStudy::with('patient')->findOrFail($id);
                    break;
                default:
                    return response()->json(['message' => 'Invalid report type'], 400);
            }

            return response()->json($report);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export reports in various formats
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'json');
            $reports = $this->index($request)->getData();

            switch ($format) {
                case 'csv':
                    return $this->exportCsv($reports->data);
                case 'pdf':
                    return $this->exportPdf($reports->data);
                default:
                    return response()->json($reports);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to export reports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function exportCsv($reports)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="reports.csv"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Type', 'Patient', 'Date', 'Summary']);
            
            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->id,
                    $report->type,
                    $report->patient_name,
                    $report->date,
                    $report->summary
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPdf($reports)
    {
        // For now, return JSON - PDF export can be implemented later with a PDF library
        return response()->json([
            'message' => 'PDF export not yet implemented',
            'data' => $reports
        ]);
    }
}
