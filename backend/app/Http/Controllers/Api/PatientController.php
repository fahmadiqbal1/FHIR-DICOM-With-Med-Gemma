<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Get all patients with pagination and filtering
     */
    public function index(Request $request)
    {
        try {
            $query = Patient::query();

            // Apply search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('mrn', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Apply MRN filter
            if ($request->has('mrn')) {
                $query->where('mrn', 'like', '%' . $request->mrn . '%');
            }

            // Apply date filters
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Order by latest first
            $query->orderBy('created_at', 'desc');

            // Paginate
            $perPage = $request->get('per_page', 50);
            $patients = $query->paginate($perPage);

            // Format patient data with computed name field
            $formattedPatients = $patients->getCollection()->map(function ($patient) {
                $first = $patient->first_name ? $patient->first_name : '';
                $last = $patient->last_name ? $patient->last_name : '';
                
                return [
                    'id' => $patient->id,
                    'uuid' => $patient->uuid,
                    'mrn' => $patient->mrn,
                    'first_name' => $patient->first_name,
                    'last_name' => $patient->last_name,
                    'name' => trim($first . ' ' . $last),
                    'dob' => $patient->dob,
                    'sex' => $patient->sex,
                    'phone' => $patient->phone,
                    'email' => $patient->email,
                    'address' => $patient->address,
                    'created_at' => $patient->created_at,
                    'updated_at' => $patient->updated_at,
                ];
            });

            // Set the formatted collection back to the paginator
            $patients->setCollection($formattedPatients);

            return response()->json([
                'data' => $patients->items(),
                'total' => $patients->total(),
                'page' => $patients->currentPage(),
                'per_page' => $patients->perPage(),
                'total_pages' => $patients->lastPage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load patients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new patient
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mrn' => 'required|string|max:255|unique:patients,mrn',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'dob' => 'nullable|date',
                'sex' => 'nullable|in:male,female,other,unknown',
                'phone' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $patientData = $request->all();
            $patientData['uuid'] = Str::uuid();
            
            $patient = Patient::create($patientData);
            
            return response()->json([
                'message' => 'Patient created successfully',
                'data' => $patient
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create patient',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific patient with related data
     */
    public function show(Patient $patient)
    {
        try {
            $patient->load([
                'imagingStudies' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'labOrders.test',
                'prescriptions.medication',
                'appointments'
            ]);

            return response()->json($patient);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load patient',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a patient
     */
    public function update(Request $request, Patient $patient)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mrn' => 'required|string|max:255|unique:patients,mrn,' . $patient->id,
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'dob' => 'nullable|date',
                'sex' => 'nullable|in:male,female,other,unknown',
                'phone' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $patient->update($request->all());
            
            return response()->json([
                'message' => 'Patient updated successfully',
                'data' => $patient
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update patient',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a patient
     */
    public function destroy(Patient $patient)
    {
        try {
            $patient->delete();
            
            return response()->json([
                'message' => 'Patient deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete patient',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get patient's imaging studies
     */
    public function studies(Patient $patient)
    {
        try {
            $studies = $patient->imagingStudies()
                ->select('id', 'description', 'modality', 'started_at', 'status')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($studies);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load patient studies',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
