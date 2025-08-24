<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Medical Supplies',
            'type' => $this->faker->randomElement([
                'medical_equipment',
                'pharmaceuticals',
                'laboratory_supplies',
                'maintenance_services',
                'it_services',
                'facility_management'
            ]),
            'contact_person' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'tax_id' => $this->faker->optional(0.8)->regexify('TAX-[0-9]{9}'),
            'payment_terms' => $this->faker->randomElement([
                'Net 30',
                '2/10 Net 30',
                'COD',
                'Net 15',
                'Upon Receipt'
            ]),
            'credit_limit' => $this->faker->optional(0.7)->randomFloat(2, 5000, 100000),
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending', 'suspended']),
            'category' => $this->faker->optional(0.6)->randomElement([
                'medical_equipment',
                'pharmaceuticals',
                'laboratory_supplies',
                'maintenance_services',
                'it_services'
            ]),
            'notes' => $this->faker->optional(0.4)->paragraph,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function medicalEquipment(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'medical_equipment',
            'name' => $this->faker->company . ' Medical Equipment',
        ]);
    }

    public function pharmaceuticals(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'pharmaceuticals',
            'name' => $this->faker->company . ' Pharmaceuticals',
        ]);
    }

    public function laboratorySupplies(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'laboratory_supplies',
            'name' => $this->faker->company . ' Lab Supplies',
        ]);
    }
}
