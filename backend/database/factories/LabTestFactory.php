<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LabTest>
 */
class LabTestFactory extends Factory
{
    public function definition(): array
    {
        $units = ['g/dL','mg/dL','mmol/L','10^3/uL','U/L','mg/L'];
        $name = $this->faker->randomElement([
            'Hemoglobin','Hematocrit','Platelets','White Blood Cells','Glucose','Creatinine','CRP','ALT','AST','Sodium','Potassium'
        ]);
        $low = $this->faker->randomFloat(1, 0, 10);
        $high = $low + $this->faker->randomFloat(1, 1, 50);
        return [
            'code' => strtoupper($this->faker->bothify('???###')),
            'name' => $name,
            'normal_range_low' => $low,
            'normal_range_high' => $high,
            'units' => $this->faker->randomElement($units),
            'specimen_type' => $this->faker->randomElement(['Blood','Serum','Plasma','Urine']),
        ];
    }
}
