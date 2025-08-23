<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    // Lab Tests Configuration
    public function getLabTests()
    {
        $tests = DB::table('lab_tests')
            ->select('id', 'code', 'name', 'category', 'normal_range', 'unit', 'price', 'is_active')
            ->orderBy('name')
            ->get();
            
        return response()->json($tests);
    }
    
    public function createLabTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:lab_tests',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'normal_range' => 'nullable|string|max:200',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $testId = DB::table('lab_tests')->insertGetId([
            'code' => $request->code,
            'name' => $request->name,
            'category' => $request->category,
            'normal_range' => $request->normal_range,
            'unit' => $request->unit,
            'price' => $request->price,
            'is_active' => $request->is_active ?? true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $test = DB::table('lab_tests')->find($testId);
        return response()->json($test, 201);
    }
    
    public function updateLabTest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:lab_tests,code,' . $id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'normal_range' => 'nullable|string|max:200',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = DB::table('lab_tests')
            ->where('id', $id)
            ->update([
                'code' => $request->code,
                'name' => $request->name,
                'category' => $request->category,
                'normal_range' => $request->normal_range,
                'unit' => $request->unit,
                'price' => $request->price,
                'is_active' => $request->is_active ?? true,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json(['error' => 'Lab test not found'], 404);
        }

        $test = DB::table('lab_tests')->find($id);
        return response()->json($test);
    }
    
    public function deleteLabTest($id)
    {
        // Check if test is being used in any lab orders
        $inUse = DB::table('lab_orders')
            ->where('test_id', $id)
            ->exists();
            
        if ($inUse) {
            return response()->json(['error' => 'Cannot delete test that is referenced in lab orders'], 400);
        }
        
        $deleted = DB::table('lab_tests')->where('id', $id)->delete();
        
        if (!$deleted) {
            return response()->json(['error' => 'Lab test not found'], 404);
        }
        
        return response()->json(['message' => 'Lab test deleted successfully']);
    }

    // Imaging Tests Configuration
    public function getImagingTests()
    {
        $tests = DB::table('imaging_test_types')
            ->select('id', 'code', 'name', 'modality', 'body_part', 'estimated_duration', 'description', 'preparation_instructions', 'is_active')
            ->orderBy('code')
            ->get();
            
        return response()->json($tests);
    }
    
    public function createImagingTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:imaging_test_types',
            'name' => 'required|string|max:255',
            'modality' => 'required|string|in:X-RAY,CT,MRI,US,MAMMO,FLUORO,NM,PET,OTHER',
            'body_part' => 'nullable|string|max:100',
            'estimated_duration' => 'nullable|integer|min:1|max:999',
            'description' => 'nullable|string|max:1000',
            'preparation_instructions' => 'nullable|string|max:2000',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $testId = DB::table('imaging_test_types')->insertGetId([
            'code' => $request->code,
            'name' => $request->name,
            'modality' => $request->modality,
            'body_part' => $request->body_part,
            'estimated_duration' => $request->estimated_duration,
            'description' => $request->description,
            'preparation_instructions' => is_array($request->preparation_instructions) 
                ? json_encode($request->preparation_instructions) 
                : $request->preparation_instructions,
            'is_active' => $request->is_active ?? true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $test = DB::table('imaging_test_types')->find($testId);
        return response()->json($test, 201);
    }
    
    public function updateImagingTest(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:imaging_test_types,code,' . $id,
            'name' => 'required|string|max:255',
            'modality' => 'required|string|in:X-RAY,CT,MRI,US,MAMMO,FLUORO,NM,PET,OTHER',
            'body_part' => 'nullable|string|max:100',
            'estimated_duration' => 'nullable|integer|min:1|max:999',
            'description' => 'nullable|string|max:1000',
            'preparation_instructions' => 'nullable|string|max:2000',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = DB::table('imaging_test_types')
            ->where('id', $id)
            ->update([
                'code' => $request->code,
                'name' => $request->name,
                'modality' => $request->modality,
                'body_part' => $request->body_part,
                'estimated_duration' => $request->estimated_duration,
                'description' => $request->description,
                'preparation_instructions' => is_array($request->preparation_instructions) 
                    ? json_encode($request->preparation_instructions) 
                    : $request->preparation_instructions,
                'is_active' => $request->is_active ?? true,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json(['error' => 'Imaging test not found'], 404);
        }

        $test = DB::table('imaging_test_types')->find($id);
        return response()->json($test);
    }
    
    public function deleteImagingTest($id)
    {
        // Check if test is being used in any imaging studies
        $inUse = DB::table('imaging_studies')
            ->where('test_type_id', $id)
            ->exists();
            
        if ($inUse) {
            return response()->json(['error' => 'Cannot delete test that is referenced in imaging studies'], 400);
        }
        
        $deleted = DB::table('imaging_test_types')->where('id', $id)->delete();
        
        if (!$deleted) {
            return response()->json(['error' => 'Imaging test not found'], 404);
        }
        
        return response()->json(['message' => 'Imaging test deleted successfully']);
    }
}
