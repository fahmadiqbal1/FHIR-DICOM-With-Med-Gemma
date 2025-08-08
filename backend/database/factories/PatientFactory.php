<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first = $this->faker->firstName();
        $last = $this->faker->lastName();

        return [
            'uuid' => (string) Str::uuid(),
            'mrn' => strtoupper($this->faker->bothify('MRN#######')),
            'first_name' => $first,
            'last_name' => $last,
            'dob' => $this->faker->dateTimeBetween('-95 years', '-1 years')->format('Y-m-d'),
            'sex' => $this->faker->randomElement(['male','female','other','unknown']),
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->safeEmail(),
            'address' => $this->faker->address(),
        ];
    }
}
