<?php

namespace Database\Seeders;

use App\Models\AiResult;
use App\Models\Appointment;
use App\Models\ClinicalNote;
use App\Models\DicomImage;
use App\Models\ImagingStudy;
use App\Models\InventoryItem;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Create provider users and assign roles if Spatie package is available
            $doctors = User::factory()->count(3)->create();
            foreach ($doctors as $u) { 
                if (method_exists($u, 'assignRole')) {
                    $u->assignRole('Doctor'); 
                }
            }
            $radiologist = User::factory()->create();
            if (method_exists($radiologist, 'assignRole')) {
                $radiologist->assignRole('Radiologist');
            }
            $pharmacist = User::factory()->create();
            if (method_exists($pharmacist, 'assignRole')) {
                $pharmacist->assignRole('Pharmacist');
            }
            $labTech = User::factory()->create();
            if (method_exists($labTech, 'assignRole')) {
                $labTech->assignRole('Lab Technician');
            }

            // Patients
            $patients = Patient::factory()->count(15)->create();

            // Medications & Inventory
            $medications = Medication::factory()->count(12)->create();
            foreach ($medications as $med) {
                InventoryItem::factory()->count(2)->for($med)->create();
            }

            // Lab Tests
            $tests = [
                ['code' => 'HB', 'name' => 'Hemoglobin', 'normal_range_low' => 12.0, 'normal_range_high' => 16.0, 'units' => 'g/dL', 'specimen_type' => 'Blood'],
                ['code' => 'WBC', 'name' => 'White Blood Cells', 'normal_range_low' => 4.0, 'normal_range_high' => 11.0, 'units' => '10^3/uL', 'specimen_type' => 'Blood'],
                ['code' => 'PLT', 'name' => 'Platelets', 'normal_range_low' => 150.0, 'normal_range_high' => 450.0, 'units' => '10^3/uL', 'specimen_type' => 'Blood'],
                ['code' => 'GLU', 'name' => 'Glucose (Fasting)', 'normal_range_low' => 70.0, 'normal_range_high' => 99.0, 'units' => 'mg/dL', 'specimen_type' => 'Blood'],
                ['code' => 'CRP', 'name' => 'C-Reactive Protein', 'normal_range_low' => 0.0, 'normal_range_high' => 3.0, 'units' => 'mg/L', 'specimen_type' => 'Blood'],
            ];
            foreach ($tests as $t) {
                LabTest::updateOrCreate(['code' => $t['code']], $t);
            }
            $labTests = LabTest::all();

            // Imaging Studies with images and AI results
            foreach ($patients->random(10) as $patient) {
                $studies = ImagingStudy::factory()->count(rand(1,2))->for($patient)->create();
                foreach ($studies as $study) {
                    DicomImage::factory()->count(3)->for($study)->create();
                    AiResult::factory()->for($study)->create();
                }
            }

            // Prescriptions
            for ($i = 0; $i < 20; $i++) {
                $patient = $patients->random();
                $med = $medications->random();
                $doc = $doctors->random();
                Prescription::create([
                    'patient_id' => $patient->id,
                    'medication_id' => $med->id,
                    'prescribed_by' => $doc->id,
                    'dosage' => $med->strength ?? '1 unit',
                    'frequency' => '1 dose twice daily',
                    'route' => 'oral',
                    'quantity' => rand(10, 60),
                    'refills_allowed' => rand(0, 3),
                    'refills_used' => rand(0, 1),
                    'status' => collect(['pending','filled','cancelled'])->random(),
                    'notes' => 'Take with food',
                ]);
            }

            // Lab Orders
            for ($i = 0; $i < 18; $i++) {
                $patient = $patients->random();
                $test = $labTests->random();
                $doc = $doctors->random();
                $orderedAt = Carbon::now()->subDays(rand(0,7))->subHours(rand(0,23));
                $status = collect(['ordered','collected','processing','resulted'])->random();
                $collectedAt = in_array($status, ['collected','processing','resulted']) ? (clone $orderedAt)->addHours(rand(1,6)) : null;
                $resultedAt = $status === 'resulted' ? (clone $collectedAt)->addHours(rand(1,12)) : null;
                $resultValue = null; $resultFlag = null; $resultNotes = null;
                if ($status === 'resulted') {
                    $rangeLow = $test->normal_range_low; $rangeHigh = $test->normal_range_high;
                    $value = is_null($rangeLow) || is_null($rangeHigh)
                        ? rand(1,100)
                        : round($rangeLow + (mt_rand()/mt_getrandmax()) * ($rangeHigh - $rangeLow) * (rand(0,10) > 8 ? 1.5 : 1.0), 2);
                    $resultValue = (string)$value;
                    if (!is_null($rangeLow) && $value < $rangeLow) $resultFlag = 'low';
                    elseif (!is_null($rangeHigh) && $value > $rangeHigh) $resultFlag = 'high';
                    else $resultFlag = 'normal';
                    if ($resultFlag !== 'normal' && rand(0,10) > 8) $resultFlag = 'critical';
                    $resultNotes = 'Auto-generated demo result';
                }
                LabOrder::create([
                    'patient_id' => $patient->id,
                    'lab_test_id' => $test->id,
                    'ordered_by' => $doc->id,
                    'priority' => collect(['routine','urgent','stat'])->random(),
                    'status' => $status,
                    'ordered_at' => $orderedAt,
                    'collected_at' => $collectedAt,
                    'resulted_at' => $resultedAt,
                    'result_value' => $resultValue,
                    'result_flag' => $resultFlag,
                    'result_notes' => $resultNotes,
                ]);
            }

            // Appointments
            for ($i = 0; $i < 20; $i++) {
                $patient = $patients->random();
                $provider = $doctors->random();
                $when = Carbon::now()->addDays(rand(-7, 14))->setTime(rand(8,16), [0,15,30,45][rand(0,3)]);
                Appointment::create([
                    'patient_id' => $patient->id,
                    'provider_id' => $provider->id,
                    'scheduled_at' => $when,
                    'duration_minutes' => collect([15,30,45,60])->random(),
                    'status' => collect(['scheduled','checked-in','completed','cancelled','no-show'])->random(),
                    'location' => 'Room '.rand(100, 120),
                    'telemedicine_url' => rand(0,1) ? null : 'https://tele.example.com/'.$patient->uuid,
                    'notes' => 'Demo appointment',
                ]);
            }

            // Clinical Notes
            for ($i = 0; $i < 20; $i++) {
                $patient = $patients->random();
                $provider = $doctors->random();
                ClinicalNote::create([
                    'patient_id' => $patient->id,
                    'provider_id' => $provider->id,
                    'soap_subjective' => 'Patient presents with demo symptoms including mild headache and fatigue.',
                    'soap_objective' => 'Vitals stable. No acute distress.',
                    'soap_assessment' => 'Likely viral syndrome. Rule out anemia.',
                    'soap_plan' => 'Hydration, rest, OTC analgesics. Order labs.',
                    'icd10_code' => collect(['R51','J06.9','Z00.00'])->random(),
                    'cpt_code' => collect(['99213','99214','99203'])->random(),
                ]);
            }
        });
    }
}
