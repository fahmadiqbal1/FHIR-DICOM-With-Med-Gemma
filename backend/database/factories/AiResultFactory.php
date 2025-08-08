<?php

namespace Database\Factories;

use App\Models\ImagingStudy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiResult>
 */
class AiResultFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['queued','processing','completed','failed']);
        return [
            'imaging_study_id' => ImagingStudy::factory(),
            'model' => 'medgemma',
            'request_id' => 'req_'.$this->faker->unique()->bothify('########'),
            'status' => $status,
            'confidence_score' => $status === 'completed' ? $this->faker->randomFloat(2, 60, 99) : null,
            'result' => $status === 'completed' ? [
                'findings' => $this->faker->sentences(2),
                'impressions' => $this->faker->sentences(1),
            ] : null,
        ];
    }
}
