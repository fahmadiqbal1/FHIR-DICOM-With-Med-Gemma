<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Office Supplies',
                'description' => 'General office supplies and stationery',
                'is_active' => true
            ],
            [
                'name' => 'Medical Equipment',
                'description' => 'Medical equipment and devices',
                'is_active' => true
            ],
            [
                'name' => 'Utilities',
                'description' => 'Electricity, water, internet bills',
                'is_active' => true
            ],
            [
                'name' => 'Staff Salaries',
                'description' => 'Non-doctor staff salaries and benefits',
                'is_active' => true
            ],
            [
                'name' => 'Marketing',
                'description' => 'Advertising and promotional expenses',
                'is_active' => true
            ],
            [
                'name' => 'Maintenance',
                'description' => 'Equipment and facility maintenance',
                'is_active' => true
            ],
            [
                'name' => 'Insurance',
                'description' => 'Medical malpractice and general insurance',
                'is_active' => true
            ],
            [
                'name' => 'Software/IT',
                'description' => 'Software licenses and IT services',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
