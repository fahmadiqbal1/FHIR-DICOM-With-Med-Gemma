<?php

namespace Database\Factories;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['available','reserved','expired','damaged'];
        return [
            'medication_id' => Medication::factory(),
            'lot_number' => strtoupper($this->faker->bothify('LOT########')),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'quantity' => $this->faker->numberBetween(10, 500),
            'location' => 'Pharmacy Aisle '.$this->faker->randomElement(['A','B','C']).'-'.$this->faker->numberBetween(1, 10),
            'cost_price' => $this->faker->randomFloat(2, 1, 500),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
