<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\ImagingTestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    /**
     * Get all lab tests for dropdown/configuration
     */
    public function getLabTests()
    {
        try {
            $tests = LabTest::orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $tests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lab tests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new lab test
     */
    public function createLabTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:lab_tests',
            'name' => 'required|string|max:255',
            'units' => 'nullable|string|max:50',
            'specimen_type' => 'required|string|max:100',
            'normal_range_low' => 'nullable|numeric',
            'normal_range_high' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $test = LabTest::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Lab test created successfully',
                'data' => $test
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create lab test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update lab test
     */
    public function updateLabTest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:lab_tests,code,' . $id,
            'name' => 'required|string|max:255',
            'units' => 'nullable|string|max:50',
            'specimen_type' => 'required|string|max:100',
            'normal_range_low' => 'nullable|numeric',
            'normal_range_high' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $test = LabTest::findOrFail($id);
            $test->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Lab test updated successfully',
                'data' => $test
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lab test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete lab test
     */
    public function deleteLabTest($id)
    {
        try {
            $test = LabTest::findOrFail($id);
            
            // Check if test is being used in any orders
            $orderCount = \App\Models\LabOrder::where('lab_test_id', $id)->count();
            if ($orderCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete lab test as it is being used in ' . $orderCount . ' order(s)'
                ], 422);
            }
            
            $test->delete();
            return response()->json([
                'success' => true,
                'message' => 'Lab test deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete lab test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all imaging tests for dropdown/configuration
     */
    public function getImagingTests()
    {
        try {
            $tests = ImagingTestType::orderBy('modality')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $tests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch imaging tests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new imaging test
     */
    public function createImagingTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:imaging_test_types',
            'name' => 'required|string|max:255',
            'modality' => 'required|string|max:100',
            'description' => 'nullable|string',
            'body_part' => 'nullable|string|max:100',
            'preparation_instructions' => 'nullable|array',
            'estimated_duration' => 'nullable|numeric|min:0|max:999',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $test = ImagingTestType::create(array_merge($request->all(), [
                'is_active' => $request->boolean('is_active', true)
            ]));
            return response()->json([
                'success' => true,
                'message' => 'Imaging test created successfully',
                'data' => $test
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create imaging test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update imaging test
     */
    public function updateImagingTest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:imaging_test_types,code,' . $id,
            'name' => 'required|string|max:255',
            'modality' => 'required|string|max:100',
            'description' => 'nullable|string',
            'body_part' => 'nullable|string|max:100',
            'preparation_instructions' => 'nullable|array',
            'estimated_duration' => 'nullable|numeric|min:0|max:999',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $test = ImagingTestType::findOrFail($id);
            $test->update(array_merge($request->all(), [
                'is_active' => $request->boolean('is_active', true)
            ]));
            return response()->json([
                'success' => true,
                'message' => 'Imaging test updated successfully',
                'data' => $test
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update imaging test',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete imaging test
     */
    public function deleteImagingTest($id)
    {
        try {
            $test = ImagingTestType::findOrFail($id);
            
            // Check if test is being used in any studies
            $studyCount = \App\Models\ImagingStudy::where('modality', $test->modality)
                ->where('description', 'LIKE', '%' . $test->name . '%')
                ->count();
            if ($studyCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete imaging test as it may be referenced in ' . $studyCount . ' study(ies)'
                ], 422);
            }
            
            $test->delete();
            return response()->json([
                'success' => true,
                'message' => 'Imaging test deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete imaging test',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
