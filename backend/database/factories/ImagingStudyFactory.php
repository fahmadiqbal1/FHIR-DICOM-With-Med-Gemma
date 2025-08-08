<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImagingStudy>
 */
class ImagingStudyFactory extends Factory
{
    public function definition(): array
    {
        $modalities = ['CT','MR','US','CR','XA','MG','NM'];
        return [
            'uuid' => (string) Str::uuid(),
            'patient_id' => Patient::factory(),
            'accession_number' => strtoupper($this->faker->bothify('ACC#######')),
            'study_instance_uid' => '2.25.'.$this->faker->unique()->numerify(str_repeat('#', 20)),
            'description' => $this->faker->sentence(4),
            'modality' => $this->faker->randomElement($modalities),
            'started_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'status' => $this->faker->randomElement(['registered','available','cancelled','entered-in-error','unknown']),
        ];
    }
}
