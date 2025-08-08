<?php

namespace Database\Factories;

use App\Models\Medication;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prescription>
 */
class PrescriptionFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending','filled','cancelled','expired']);
        return [
            'patient_id' => Patient::factory(),
            'medication_id' => Medication::factory(),
            'prescribed_by' => User::factory(),
            'dosage' => $this->faker->randomElement(['5 mg','10 mg','250 mg','500 mg','1 g']),
            'frequency' => $this->faker->randomElement(['once daily','twice daily','three times daily','at bedtime']),
            'route' => $this->faker->randomElement(['oral','iv','im','subcutaneous','topical']),
            'quantity' => $this->faker->numberBetween(10, 60),
            'refills_allowed' => $this->faker->numberBetween(0, 3),
            'refills_used' => $this->faker->numberBetween(0, 1),
            'status' => $status,
            'notes' => $this->faker->sentence(),
        ];
    }
}
