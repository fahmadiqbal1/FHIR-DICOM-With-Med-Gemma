<?php

namespace Database\Factories;

use App\Models\LabTest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LabOrder>
 */
class LabOrderFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['ordered','collected','processing','resulted','cancelled']);
        $orderedAt = Carbon::now()->subDays(rand(0,7))->subHours(rand(0,23));
        $collectedAt = in_array($status, ['collected','processing','resulted']) ? (clone $orderedAt)->addHours(rand(1,6)) : null;
        $resultedAt = $status === 'resulted' ? (clone $collectedAt)->addHours(rand(1,12)) : null;
        $resultValue = null; $resultFlag = null; $resultNotes = null;
        if ($status === 'resulted') {
            $value = (string) $this->faker->randomFloat(2, 0, 2000);
            $resultValue = $value;
            $resultFlag = $this->faker->randomElement(['normal','high','low','critical']);
            $resultNotes = 'Factory generated result';
        }
        return [
            'patient_id' => Patient::factory(),
            'lab_test_id' => LabTest::factory(),
            'ordered_by' => User::factory(),
            'priority' => $this->faker->randomElement(['routine','urgent','stat']),
            'status' => $status,
            'ordered_at' => $orderedAt,
            'collected_at' => $collectedAt,
            'resulted_at' => $resultedAt,
            'result_value' => $resultValue,
            'result_flag' => $resultFlag,
            'result_notes' => $resultNotes,
        ];
    }
}
