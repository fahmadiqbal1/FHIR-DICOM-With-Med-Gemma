<?php

namespace Database\Factories;

use App\Models\ImagingStudy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DicomImage>
 */
class DicomImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'imaging_study_id' => ImagingStudy::factory(),
            'series_instance_uid' => '2.25.'.$this->faker->unique()->numerify(str_repeat('#', 20)),
            'sop_instance_uid' => '2.25.'.$this->faker->unique()->numerify(str_repeat('#', 20)),
            'file_path' => 'secure/'.Str::uuid().'.dcm.enc',
            'size_bytes' => $this->faker->numberBetween(200_000, 5_000_000),
            'checksum' => $this->faker->sha256(),
            'metadata' => [
                'PatientName' => $this->faker->name(),
                'StudyDate' => $this->faker->date('Ymd'),
            ],
            'width' => $this->faker->randomElement([512, 1024, 2048]),
            'height' => $this->faker->randomElement([512, 1024, 2048]),
            'frames' => $this->faker->numberBetween(1, 3),
            'content_type' => 'application/dicom',
        ];
    }
}
