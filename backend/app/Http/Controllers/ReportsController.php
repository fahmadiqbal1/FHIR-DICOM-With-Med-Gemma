<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function patients(Request $request)
    {
        $patients = Patient::query()
            ->withCount(['imagingStudies','labOrders','prescriptions','clinicalNotes'])
            ->orderBy('id')
            ->limit(250)
            ->get();

        $data = $patients->map(function ($p) {
            $first = $p->first_name ? $p->first_name : '';
            $last = $p->last_name ? $p->last_name : '';
            // Safely format DOB regardless of encryption/casting behavior
            $dobVal = $p->dob;
            $dob = null;
            if ($dobVal instanceof \DateTimeInterface) {
                $dob = $dobVal->format('Y-m-d');
            } elseif (is_string($dobVal) && $dobVal !== '') {
                try { $dob = \Illuminate\Support\Carbon::parse($dobVal)->toDateString(); } catch (\Throwable $e) { $dob = $dobVal; }
            }
            return [
                'id' => $p->id,
                'uuid' => $p->uuid,
                'mrn' => $p->mrn,
                'first_name' => $p->first_name,
                'last_name' => $p->last_name,
                'name' => trim($first.' '.$last),
                'dob' => $dob,
                'sex' => $p->sex,
                'counts' => [
                    'imaging_studies' => $p->imaging_studies_count,
                    'lab_orders' => $p->lab_orders_count,
                    'prescriptions' => $p->prescriptions_count,
                    'clinical_notes' => $p->clinical_notes_count,
                ],
            ];
        })->values();

        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json(['data' => $data]);
        }

        return redirect('/app');
    }

    public function patientShow(Request $request, Patient $patient)
    {
        $patient->load([
            'imagingStudies' => function ($q) { $q->orderByDesc('started_at'); },
            'imagingStudies.images',
            'imagingStudies.aiResults' => function ($q) { $q->orderByDesc('created_at'); },
            'labOrders.test',
            'labOrders.orderingProvider',
            'prescriptions.medication',
            'prescriptions.prescriber',
            'clinicalNotes' => function ($q) { $q->orderByDesc('created_at'); },
        ]);

        $first = $patient->first_name ? $patient->first_name : '';
        $last = $patient->last_name ? $patient->last_name : '';
        // Safe DOB formatting
        $dobVal = $patient->dob;
        $dob = null;
        if ($dobVal instanceof \DateTimeInterface) {
            $dob = $dobVal->format('Y-m-d');
        } elseif (is_string($dobVal) && $dobVal !== '') {
            try { $dob = \Illuminate\Support\Carbon::parse($dobVal)->toDateString(); } catch (\Throwable $e) { $dob = $dobVal; }
        }

        $payload = [
            'id' => $patient->id,
            'uuid' => $patient->uuid,
            'mrn' => $patient->mrn,
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'name' => trim($first.' '.$last),
            'dob' => $dob,
            'sex' => $patient->sex,
            'imaging_studies' => $patient->imagingStudies->map(function ($s) {
                return [
                    'id' => $s->id,
                    'uuid' => $s->uuid,
                    'description' => $s->description,
                    'modality' => $s->modality,
                    'started_at' => $s->started_at ? $s->started_at->toDateTimeString() : null,
                    'status' => $s->status,
                    'images_count' => $s->images->count(),
                    'ai_results' => $s->aiResults->map(function ($r) {
                        return [
                            'id' => $r->id,
                            'model' => $r->model,
                            'status' => $r->status,
                            'confidence_score' => $r->confidence_score,
                            'result' => $r->result,
                            'created_at' => $r->created_at ? $r->created_at->toDateTimeString() : null,
                        ];
                    })->values(),
                ];
            })->values(),
            'lab_orders' => $patient->labOrders->map(function ($o) {
                $code = $o->test ? $o->test->code : null;
                $name = $o->test ? $o->test->name : null;
                return [
                    'id' => $o->id,
                    'code' => $code,
                    'name' => $name,
                    'status' => $o->status,
                    'priority' => $o->priority,
                    'ordered_at' => $o->ordered_at ? $o->ordered_at->toDateTimeString() : null,
                    'result_value' => $o->result_value,
                    'result_flag' => $o->result_flag,
                    'result_notes' => $o->result_notes,
                ];
            })->values(),
            'prescriptions' => $patient->prescriptions->map(function ($p) {
                $medName = $p->medication ? $p->medication->name : null;
                $medStrength = $p->medication ? $p->medication->strength : null;
                return [
                    'id' => $p->id,
                    'medication' => $medName,
                    'strength' => $medStrength,
                    'dosage' => $p->dosage,
                    'frequency' => $p->frequency,
                    'route' => $p->route,
                    'quantity' => $p->quantity,
                    'status' => $p->status,
                    'notes' => $p->notes,
                ];
            })->values(),
            'clinical_notes' => $patient->clinicalNotes->map(function ($n) {
                return [
                    'id' => $n->id,
                    'soap_subjective' => $n->soap_subjective,
                    'soap_objective' => $n->soap_objective,
                    'soap_assessment' => $n->soap_assessment,
                    'soap_plan' => $n->soap_plan,
                    'created_at' => $n->created_at ? $n->created_at->toDateTimeString() : null,
                ];
            })->values(),
        ];

        return response()->json($payload);
    }
}
