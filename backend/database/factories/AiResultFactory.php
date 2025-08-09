<?php

namespace Database\Factories;

use App\Models\AiResult;
use App\Models\ImagingStudy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiResult>
 */
class AiResultFactory extends Factory
{
    protected $model = AiResult::class;

    public function definition(): array
    {
        $mod = $this->faker->randomElement(['CR','US','CT','MR']);
        $payload = [
            'study_uuid' => (string) Str::uuid(),
            'modality' => $mod,
            'findings' => ['No acute abnormality detected'],
            'impression' => 'Unremarkable study',
            'recommendations' => ['Clinical correlation recommended'],
        ];

        return [
            'imaging_study_id' => ImagingStudy::factory(),
            'model' => 'medgemma',
            'request_id' => (string) Str::uuid(),
            'status' => 'completed',
            'confidence_score' => $this->faker->randomFloat(2, 0.70, 0.95),
            'result' => $payload,
        ];
    }
}
