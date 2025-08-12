<?php

namespace App\Http\Controllers;

use App\Models\DicomImage;
use App\Models\ImagingStudy;
use App\Models\Patient;
use App\Services\SecureStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DicomController extends Controller
{
    /**
     * Upload a DICOM file and associate it with an imaging study
     */
    public function upload(Request $request, SecureStorageService $storageService)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:50000', // 50MB max
            'patient_id' => 'required|exists:patients,id',
            'study_instance_uid' => 'nullable|string|max:255',
            'series_instance_uid' => 'nullable|string|max:255',
            'sop_instance_uid' => 'nullable|string|max:255',
            'modality' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $file = $request->file('file');
            $fileContents = file_get_contents($file->getRealPath());
            $fileSize = $file->getSize();
            $fileChecksum = md5($fileContents);
            
            // Extract DICOM metadata (simplified for demo)
            $metadata = [
                'filename' => $file->getClientOriginalName(),
                'size' => $fileSize,
                'checksum' => $fileChecksum,
                'uploaded_at' => now()->toIso8601String(),
            ];
            
            // Use provided UIDs or generate new ones
            $studyInstanceUid = $request->input('study_instance_uid') ?? '1.2.3.' . Str::uuid()->toString();
            $seriesInstanceUid = $request->input('series_instance_uid') ?? '1.2.3.' . Str::uuid()->toString();
            $sopInstanceUid = $request->input('sop_instance_uid') ?? '1.2.3.' . Str::uuid()->toString();
            
            // Store file securely
            $filePath = $storageService->store($fileContents, 'dcm');
            
            // Find or create imaging study
            $study = ImagingStudy::firstOrCreate(
                ['study_instance_uid' => $studyInstanceUid],
                [
                    'uuid' => Str::uuid(),
                    'patient_id' => $request->input('patient_id'),
                    'description' => $request->input('description') ?? 'Uploaded DICOM study',
                    'modality' => $request->input('modality') ?? 'OT', // Other
                    'started_at' => now(),
                    'status' => 'available',
                ]
            );
            
            // Create DICOM image record
            $dicomImage = DicomImage::create([
                'imaging_study_id' => $study->id,
                'series_instance_uid' => $seriesInstanceUid,
                'sop_instance_uid' => $sopInstanceUid,
                'file_path' => $filePath,
                'size_bytes' => $fileSize,
                'checksum' => $fileChecksum,
                'metadata' => $metadata,
                'content_type' => $file->getMimeType() ?? 'application/dicom',
            ]);
            
            return response()->json([
                'message' => 'DICOM file uploaded successfully',
                'study_id' => $study->id,
                'image_id' => $dicomImage->id,
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('DICOM upload failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to upload DICOM file: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Convert DICOM to FHIR format
     */
    public function exportToFhir(ImagingStudy $study)
    {
        try {
            $patient = $study->patient;
            
            // Create FHIR ImagingStudy resource
            $fhirResource = [
                'resourceType' => 'ImagingStudy',
                'id' => $study->uuid,
                'status' => $study->status,
                'subject' => [
                    'reference' => 'Patient/' . $patient->uuid,
                    'display' => $patient->first_name . ' ' . $patient->last_name,
                ],
                'started' => $study->started_at ? $study->started_at->toIso8601String() : null,
                'description' => $study->description,
                'series' => [],
            ];
            
            // Group images by series
            $seriesMap = [];
            foreach ($study->images as $image) {
                if (!isset($seriesMap[$image->series_instance_uid])) {
                    $seriesMap[$image->series_instance_uid] = [
                        'uid' => $image->series_instance_uid,
                        'instances' => [],
                    ];
                }
                
                $seriesMap[$image->series_instance_uid]['instances'][] = [
                    'uid' => $image->sop_instance_uid,
                    'sopClass' => [
                        'system' => 'urn:ietf:rfc:3986',
                        'code' => '1.2.840.10008.5.1.4.1.1.1', // Computed Radiography Image Storage
                    ],
                ];
            }
            
            // Add series to FHIR resource
            foreach ($seriesMap as $seriesUid => $series) {
                $fhirResource['series'][] = [
                    'uid' => $seriesUid,
                    'modality' => [
                        'system' => 'http://dicom.nema.org/resources/ontology/DCM',
                        'code' => $study->modality ?? 'OT',
                    ],
                    'numberOfInstances' => count($series['instances']),
                    'instance' => $series['instances'],
                ];
            }
            
            return response()->json($fhirResource);
            
        } catch (\Exception $e) {
            Log::error('FHIR export failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to export to FHIR: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Download a DICOM file
     */
    public function download(DicomImage $image, SecureStorageService $storageService)
    {
        try {
            $fileContents = $storageService->retrieve($image->file_path);
            $filename = $image->metadata['filename'] ?? 'dicom-' . $image->id . '.dcm';
            
            return response($fileContents)
                ->header('Content-Type', $image->content_type ?? 'application/dicom')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            Log::error('DICOM download failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to download DICOM file: ' . $e->getMessage()], 500);
        }
    }
}