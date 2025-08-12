<?php

namespace App\Services;

use App\Models\AiResult;
use App\Models\ImagingStudy;
use App\Models\LabOrder;
use App\Models\Medication;
use App\Models\Patient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MedGemmaService
{
    public function analyzeImagingStudy(ImagingStudy $study)
    {
        $endpoint = config('services.medgemma.endpoint');
        $apiKey = config('services.medgemma.api_key');
        $model = config('services.medgemma.model', 'medgemma');
        $payload = [
            'study_uuid' => $study->uuid,
            'modality' => $study->modality,
            'description' => $study->description,
        ];
        $result = null;
        $maxRetries = 3;
        $attempt = 0;
        do {
            try {
                $response = Http::withToken($apiKey)
                    ->timeout(30)
                    ->post($endpoint . '/analyze/imaging', $payload);
                if ($response->successful()) {
                    $data = $response->json();
                    $result = $data;
                    break;
                } else {
                    Log::warning('MedGemma API error', ['status' => $response->status(), 'body' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error('MedGemma API exception', ['error' => $e->getMessage()]);
            }
            $attempt++;
            usleep(500000); // 0.5s backoff
        } while ($attempt < $maxRetries);
        if (!$result) {
            $result = [
                'error' => 'MedGemma API unavailable or failed after retries.',
                'modality' => $study->modality,
                'impression' => 'Unable to analyze due to API error.'
            ];
        }
        $ai = AiResult::create([
            'imaging_study_id' => $study->id,
            'model' => $model,
            'result' => $result,
        ]);
        return ['result' => $result];
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
