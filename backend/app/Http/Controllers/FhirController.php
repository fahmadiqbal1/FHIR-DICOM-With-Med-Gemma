<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ImagingStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FhirController extends Controller
{
    /**
     * Get a FHIR Patient resource
     */
    public function getPatient(Patient $patient)
    {
        try {
            // Convert to FHIR Patient resource
            $fhirResource = [
                'resourceType' => 'Patient',
                'id' => $patient->uuid,
                'identifier' => [
                    [
                        'system' => 'http://example.org/fhir/mrn',
                        'value' => $patient->mrn,
                    ],
                ],
                'name' => [
                    [
                        'use' => 'official',
                        'family' => $patient->last_name,
                        'given' => [$patient->first_name],
                    ],
                ],
                'gender' => strtolower($patient->sex) === 'female' ? 'female' : (strtolower($patient->sex) === 'male' ? 'male' : 'unknown'),
                'birthDate' => $patient->dob instanceof \DateTimeInterface ? $patient->dob->format('Y-m-d') : $patient->dob,
            ];
            
            return response()->json($fhirResource);
            
        } catch (\Exception $e) {
            Log::error('FHIR Patient export failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to export to FHIR: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Import a FHIR Patient resource
     */
    public function importPatient(Request $request)
    {
        try {
            $fhirData = $request->json()->all();
            
            // Validate FHIR resource
            if ($fhirData['resourceType'] !== 'Patient') {
                return response()->json(['error' => 'Invalid FHIR resource type. Expected Patient.'], 400);
            }
            
            // Extract patient data
            $mrn = null;
            foreach ($fhirData['identifier'] ?? [] as $identifier) {
                if (($identifier['system'] ?? '') === 'http://example.org/fhir/mrn') {
                    $mrn = $identifier['value'] ?? null;
                    break;
                }
            }
            
            $firstName = null;
            $lastName = null;
            foreach ($fhirData['name'] ?? [] as $name) {
                if (($name['use'] ?? '') === 'official') {
                    $lastName = $name['family'] ?? null;
                    $firstName = $name['given'][0] ?? null;
                    break;
                }
            }
            
            $gender = $fhirData['gender'] ?? null;
            $birthDate = $fhirData['birthDate'] ?? null;
            
            // Create or update patient
            $patient = Patient::updateOrCreate(
                ['uuid' => $fhirData['id'] ?? Str::uuid()],
                [
                    'mrn' => $mrn ?? 'MRN' . rand(10000, 99999),
                    'first_name' => $firstName ?? 'Unknown',
                    'last_name' => $lastName ?? 'Patient',
                    'sex' => $gender === 'female' ? 'Female' : ($gender === 'male' ? 'Male' : 'Other'),
                    'dob' => $birthDate,
                ]
            );
            
            return response()->json([
                'message' => 'FHIR Patient imported successfully',
                'patient_id' => $patient->id,
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('FHIR Patient import failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to import FHIR Patient: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get a FHIR ImagingStudy resource
     */
    public function getImagingStudy(ImagingStudy $study)
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
            Log::error('FHIR ImagingStudy export failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to export to FHIR: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Import a FHIR ImagingStudy resource
     */
    public function importImagingStudy(Request $request)
    {
        try {
            $fhirData = $request->json()->all();
            
            // Validate FHIR resource
            if ($fhirData['resourceType'] !== 'ImagingStudy') {
                return response()->json(['error' => 'Invalid FHIR resource type. Expected ImagingStudy.'], 400);
            }
            
            // Extract patient reference
            $patientReference = null;
            if (isset($fhirData['subject']['reference'])) {
                $patientReference = $fhirData['subject']['reference'];
                if (strpos($patientReference, 'Patient/') === 0) {
                    $patientUuid = substr($patientReference, 8);
                    $patient = Patient::where('uuid', $patientUuid)->first();
                    if (!$patient) {
                        return response()->json(['error' => 'Referenced patient not found.'], 404);
                    }
                } else {
                    return response()->json(['error' => 'Invalid patient reference.'], 400);
                }
            } else {
                return response()->json(['error' => 'Patient reference is required.'], 400);
            }
            
            // Create imaging study
            $study = ImagingStudy::updateOrCreate(
                ['uuid' => $fhirData['id'] ?? Str::uuid()],
                [
                    'patient_id' => $patient->id,
                    'description' => $fhirData['description'] ?? 'Imported FHIR ImagingStudy',
                    'modality' => $fhirData['modality']['code'] ?? 'OT',
                    'started_at' => $fhirData['started'] ?? now(),
                    'status' => $fhirData['status'] ?? 'available',
                    'study_instance_uid' => $fhirData['identifier'][0]['value'] ?? ('1.2.3.' . Str::uuid()->toString()),
                ]
            );
            
            return response()->json([
                'message' => 'FHIR ImagingStudy imported successfully',
                'study_id' => $study->id,
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('FHIR ImagingStudy import failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to import FHIR ImagingStudy: ' . $e->getMessage()], 500);
        }
    }
}