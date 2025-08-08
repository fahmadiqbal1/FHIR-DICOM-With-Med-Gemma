<?php

namespace App\Http\Controllers;

use App\Models\ImagingStudy;
use App\Models\Patient;
use App\Services\MedGemmaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedGemmaController extends Controller
{
    public function analyzeImagingStudy(Request $request, ImagingStudy $study, MedGemmaService $svc)
    {
        $result = $svc->analyzeImagingStudy($study);
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($result);
        }
        return redirect()->back()->with('status', 'Imaging analysis completed');
    }

    public function analyzeLabs(Request $request, Patient $patient, MedGemmaService $svc)
    {
        $result = $svc->analyzeLabs($patient);
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($result);
        }
        return redirect()->back()->with('status', 'Lab comments generated');
    }

    public function combinedSecondOpinion(Request $request, Patient $patient, MedGemmaService $svc)
    {
        $result = $svc->combinedSecondOpinion($patient);
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json($result);
        }
        return redirect()->back()->with('status', 'Combined second opinion generated');
    }
}
