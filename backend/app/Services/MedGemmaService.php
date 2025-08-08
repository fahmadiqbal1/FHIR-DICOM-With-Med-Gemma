<?php

namespace App\Services;

use App\Models\AiResult;
use App\Models\ImagingStudy;
use App\Models\LabOrder;
use App\Models\Medication;
use App\Models\Patient;
use Illuminate\Support\Arr;

class MedGemmaService
{
    public function analyzeImagingStudy(ImagingStudy $study)
    {
        // Fake analysis based on modality and description
        $mod = strtoupper((string) $study->modality);
        $desc = strtolower((string) $study->description);
        $findings = [];
        $impression = '';
        $recommendations = [];
        $confidence = 0.82;

        if ($mod === 'XR' || $mod === 'CR') {
            $findings[] = str_contains($desc, 'chest') ? 'No acute cardiopulmonary findings' : 'No displaced fracture identified';
            $impression = 'Normal chest radiograph' . (str_contains($desc, 'hand') ? '; consider sprain if symptomatic' : '');
            $recommendations[] = 'Symptomatic management and follow-up if symptoms persist';
        } elseif ($mod === 'US') {
            $findings[] = 'Viable intrauterine pregnancy' . (str_contains($desc, 'ob') ? ' with appropriate growth parameters' : '');
            $impression = 'Unremarkable obstetric ultrasound';
            $recommendations[] = 'Routine prenatal follow-up';
            $confidence = 0.88;
        } else {
            $findings[] = 'No critical abnormality detected';
            $impression = 'Unremarkable study';
            $recommendations[] = 'Clinical correlation recommended';
            $confidence = 0.75;
        }

        $payload = [
            'study_uuid' => $study->uuid,
            'modality' => $mod,
            'findings' => $findings,
            'impression' => $impression,
            'recommendations' => $recommendations,
        ];

        $ai = AiResult::create([
            'imaging_study_id' => $study->id,
            'model' => config('services.medgemma.model', 'medgemma'),
            'request_id' => (string) \Illuminate\Support\Str::uuid(),
            'status' => 'completed',
            'confidence_score' => $confidence,
            'result' => $payload,
        ]);

        return ['ai_result_id' => $ai->id, 'result' => $payload];
    }

    public function analyzeLabs(Patient $patient): array
    {
        $orders = LabOrder::where('patient_id', $patient->id)->where('status', 'resulted')->with('test')->get();
        $comments = [];
        foreach ($orders as $o) {
            $flag = strtolower((string) $o->result_flag);
            $name = $o->test ? $o->test->name : 'Unknown';
            $val = $o->result_value;
            $c = match ($flag) {
                'high' => "$name is elevated ($val). Consider further evaluation.",
                'low' => "$name is low ($val). Consider supplementation or retesting.",
                'critical' => "$name is critically abnormal ($val). Immediate clinical attention required.",
                default => "$name is within normal limits ($val).",
            };
            $comments[] = $c;
            // Append AI comment into result_notes (non-destructive)
            $note = trim((string) $o->result_notes);
            if ($note !== '') {
                $note .= "\n";
            }
            $o->result_notes = $note.'[MedGemma AI] '.$c;
            $o->save();
        }

        return [
            'patient_id' => $patient->id,
            'lab_comments' => $comments,
        ];
    }

    public function combinedSecondOpinion(Patient $patient): array
    {
        // Analyze all imaging studies first (idempotent; creates new AiResult rows each call)
        $studies = $patient->imagingStudies()->get();
        $imageSummaries = [];
        foreach ($studies as $s) {
            $imageSummaries[] = $this->analyzeImagingStudy($s)['result'];
        }

        // Analyze labs
        $labSummary = $this->analyzeLabs($patient);

        // Recommend a medication (very simplistic demo logic)
        $recMeds = [];
        $pregnant = $patient->sex === 'female' && $patient->clinicalNotes()->where('soap_assessment', 'like', '%pregnan%')->exists();
        // If we have medications present, suggest Prenatal Vitamins for pregnancy, else first available
        $med = Medication::where('name', 'like', '%Prenatal%')->first();
        if (!$med) {
            $med = Medication::first();
        }
        if ($med) {
            $recMeds[] = ['medication' => $med->name, 'dosage' => $med->strength ?? 'as directed'];
        }

        // Create a clinical note summarizing
        $summaryText = 'AI Combined Second Opinion:\n';
        foreach ($imageSummaries as $img) {
            $summaryText .= '- '.$img['modality'].': '.$img['impression']."\n";
        }
        foreach ($labSummary['lab_comments'] as $c) {
            $summaryText .= '- Lab: '.$c."\n";
        }
        if (!empty($recMeds)) {
            $summaryText .= 'Suggested meds: '.implode(', ', Arr::pluck($recMeds, 'medication'))."\n";
        }

        $patient->clinicalNotes()->create([
            'provider_id' => \App\Models\User::first()?->id ?? 1,
            'soap_subjective' => 'Follow-up review',
            'soap_objective' => 'Reviewed imaging and labs',
            'soap_assessment' => 'See AI combined second opinion',
            'soap_plan' => $summaryText,
        ]);

        return [
            'patient_id' => $patient->id,
            'imaging' => $imageSummaries,
            'labs' => $labSummary['lab_comments'],
            'medications' => $recMeds,
        ];
    }
}
