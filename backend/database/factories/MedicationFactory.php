<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medication>
 */
class MedicationFactory extends Factory
{
    public function definition(): array
    {
        $forms = ['tablet','capsule','injection','solution','suspension','ointment','cream'];
        $units = ['mg','mcg','g','mg/mL'];
        $strength = $this->faker->randomElement([
            $this->faker->numberBetween(5, 1000).' '.$this->faker->randomElement($units),
            $this->faker->numberBetween(1, 100).' '.$this->faker->randomElement($units),
        ]);
        $schedules = [null,'II','III','IV','V'];
        return [
            'ndc_code' => $this->faker->numerify('#####-####-##'),
            'name' => ucfirst($this->faker->word()).' '.$this->faker->randomElement(['HCL','SR','ER','XR','']),'form' => $this->faker->randomElement($forms),
            'strength' => $strength,
            'manufacturer' => $this->faker->company(),
            'controlled_substance_schedule' => $this->faker->randomElement($schedules),
            'is_active' => true,
        ];
    }
}
