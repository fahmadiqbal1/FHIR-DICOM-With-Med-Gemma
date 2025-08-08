<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $scheduled = Carbon::now()->addDays(rand(-7, 14))->setTime(rand(8,16), [0,15,30,45][rand(0,3)]);
        return [
            'patient_id' => Patient::factory(),
            'provider_id' => User::factory(),
            'scheduled_at' => $scheduled,
            'duration_minutes' => $this->faker->randomElement([15,30,45,60]),
            'status' => $this->faker->randomElement(['scheduled','checked-in','completed','cancelled','no-show']),
            'location' => 'Room '.$this->faker->numberBetween(100, 120),
            'telemedicine_url' => $this->faker->boolean() ? null : 'https://tele.example.com/'.$this->faker->uuid(),
            'notes' => $this->faker->sentence(),
        ];
    }
}
