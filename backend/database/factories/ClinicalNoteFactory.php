<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClinicalNote>
 */
class ClinicalNoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'provider_id' => User::factory(),
            'soap_subjective' => 'Subjective: '.$this->faker->sentence(),
            'soap_objective' => 'Objective: '.$this->faker->sentence(),
            'soap_assessment' => 'Assessment: '.$this->faker->sentence(),
            'soap_plan' => 'Plan: '.$this->faker->sentence(),
            'icd10_code' => $this->faker->randomElement(['R51','J06.9','Z00.00']),
            'cpt_code' => $this->faker->randomElement(['99213','99214','99203']),
        ];
    }
}
