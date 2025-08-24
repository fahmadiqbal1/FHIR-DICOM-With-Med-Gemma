<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['lab_request', 'work_order', 'invoice', 'system']),
            'title' => $this->faker->sentence(4),
            'message' => $this->faker->paragraph(2),
            'priority' => $this->faker->randomElement(['low', 'normal', 'high', 'urgent']),
            'read_at' => $this->faker->optional(0.3)->dateTime(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    public function labRequest(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'lab_request',
            'title' => 'New Lab Request',
            'message' => 'A new lab request has been submitted and requires your attention.',
        ]);
    }

    public function workOrder(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'work_order',
            'title' => 'Work Order Update',
            'message' => 'A work order has been updated and requires your review.',
        ]);
    }
}
