<?php

namespace App\Transformers\Fhir;

use App\Models\ImagingStudy;
use App\Models\DicomImage;
use Illuminate\Support\Str;

class ImagingStudyTransformer
{
    public function toFhir(ImagingStudy $study): array
    {
        $series = [];
        $bySeries = $study->images->groupBy('series_instance_uid');
        foreach ($bySeries as $seriesUid => $instances) {
            $series[] = [
                'uid' => $seriesUid,
                'numberOfInstances' => $instances->count(),
                'instance' => $instances->map(function (DicomImage $img) {
                    return [
                        'uid' => $img->sop_instance_uid,
                        'number' => null,
                        'title' => null,
                    ];
                })->values()->all(),
            ];
        }

        return [
            'resourceType' => 'ImagingStudy',
            'id' => (string) $study->uuid,
            'identifier' => array_filter([
                $study->accession_number ? [
                    'system' => 'urn:oid:2.16.840.1.113883.6.233',
                    'value' => $study->accession_number,
                ] : null,
                [
                    'system' => 'urn:dicom:uid',
                    'value' => $study->study_instance_uid,
                ],
            ]),
            'status' => $study->status,
            'modality' => $study->modality ? [[
                'system' => 'http://dicom.nema.org/resources/ontology/DCM',
                'code' => $study->modality,
            ]] : [],
            'subject' => [
                'reference' => 'Patient/'.(string) $study->patient->uuid,
                'display' => trim($study->patient->first_name.' '.$study->patient->last_name),
            ],
            'started' => optional($study->started_at)->toIso8601String(),
            'description' => $study->description,
            'series' => $series,
        ];
    }

    public function toBundle($studies): array
    {
        return [
            'resourceType' => 'Bundle',
            'type' => 'searchset',
            'total' => $studies->count(),
            'entry' => $studies->map(function (ImagingStudy $study) {
                return [
                    'fullUrl' => url('/api/fhir/ImagingStudy/'.$study->uuid),
                    'resource' => $this->toFhir($study),
                ];
            })->values()->all(),
        ];
    }
}
