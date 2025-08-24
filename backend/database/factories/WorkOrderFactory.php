<?php

namespace Database\Factories;

use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        $estimatedCost = $this->faker->randomFloat(2, 100, 50000);
        $isCompleted = $this->faker->boolean(30); // 30% chance of being completed
        
        return [
            'title' => $this->faker->randomElement([
                'Replace CT Scanner',
                'Repair MRI Machine',
                'Install New X-Ray Equipment',
                'Upgrade Laboratory Equipment',
                'Maintain HVAC System',
                'Replace Network Infrastructure',
                'Update Software Systems',
                'Repair Surgical Equipment'
            ]),
            'description' => $this->faker->paragraph(3),
            'user_id' => User::factory(),
            'supplier_id' => Supplier::factory(),
            'assigned_to' => User::factory(),
            'priority' => $this->faker->randomElement(['low', 'normal', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'estimated_cost' => $estimatedCost,
            'actual_cost' => $isCompleted ? $this->faker->randomFloat(2, $estimatedCost * 0.8, $estimatedCost * 1.3) : null,
            'due_date' => $this->faker->dateTimeBetween('now', '+3 months'),
            'completed_at' => $isCompleted ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'location' => $this->faker->randomElement([
                'Radiology Department',
                'Laboratory',
                'Surgery Department',
                'Emergency Room',
                'ICU',
                'Pharmacy',
                'IT Department',
                'Maintenance Room'
            ]),
            'category' => $this->faker->randomElement([
                'equipment_maintenance',
                'equipment_replacement',
                'installation',
                'repair',
                'upgrade',
                'inspection',
                'pharmaceuticals',
                'it_services'
            ]),
            'notes' => $this->faker->optional(0.6)->paragraph,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'actual_cost' => null,
            'completed_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'actual_cost' => null,
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'actual_cost' => $this->faker->randomFloat(2, $attributes['estimated_cost'] * 0.8, $attributes['estimated_cost'] * 1.3),
            'completed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'actual_cost' => null,
            'completed_at' => null,
            'notes' => 'Cancelled due to budget constraints or change in requirements.',
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
            'due_date' => $this->faker->dateTimeBetween('now', '+1 week'),
        ]);
    }

    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'estimated_cost' => $this->faker->randomFloat(2, 25000, 100000),
        ]);
    }

    public function equipmentMaintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'equipment_maintenance',
            'title' => 'Equipment Maintenance - ' . $this->faker->randomElement(['CT Scanner', 'MRI Machine', 'X-Ray Unit']),
        ]);
    }

    public function equipmentReplacement(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'equipment_replacement',
            'title' => 'Equipment Replacement - ' . $this->faker->randomElement(['Ultrasound Machine', 'Lab Analyzer', 'Ventilator']),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
            'status' => $this->faker->randomElement(['pending', 'in_progress']),
        ]);
    }
}
