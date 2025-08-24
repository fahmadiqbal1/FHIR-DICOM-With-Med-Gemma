<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LabTest;

class LabTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labTests = [
            // === Mission HA-360 Hematology Tests ===
            [
                'code' => 'WBC',
                'name' => 'White Blood Cell Count',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'K/μL',
                'normal_range' => '4.0-10.0',
                'normal_range_low' => 4.0,
                'normal_range_high' => 10.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'RBC',
                'name' => 'Red Blood Cell Count',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'M/μL',
                'normal_range' => 'M: 4.2-5.4, F: 3.6-5.0',
                'normal_range_low' => 3.6,
                'normal_range_high' => 5.4,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'HGB',
                'name' => 'Hemoglobin',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'g/dL',
                'normal_range' => 'M: 12.0-15.5, F: 12.0-15.5',
                'normal_range_low' => 12.0,
                'normal_range_high' => 15.5,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'HCT',
                'name' => 'Hematocrit',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => '%',
                'normal_range' => 'M: 37.0-47.0, F: 36.0-44.0',
                'normal_range_low' => 36.0,
                'normal_range_high' => 47.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'PLT',
                'name' => 'Platelet Count',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'K/μL',
                'normal_range' => '150-450',
                'normal_range_low' => 150.0,
                'normal_range_high' => 450.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'MCV',
                'name' => 'Mean Cell Volume',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'fL',
                'normal_range' => '80.0-100.0',
                'normal_range_low' => 80.0,
                'normal_range_high' => 100.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'MCH',
                'name' => 'Mean Cell Hemoglobin',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'pg',
                'normal_range' => '27.0-32.0',
                'normal_range_low' => 27.0,
                'normal_range_high' => 32.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'MCHC',
                'name' => 'Mean Cell Hemoglobin Concentration',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'g/dL',
                'normal_range' => '32.0-36.0',
                'normal_range_low' => 32.0,
                'normal_range_high' => 36.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'RDW',
                'name' => 'Red Cell Distribution Width',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => '%',
                'normal_range' => '11.5-14.5',
                'normal_range_low' => 11.5,
                'normal_range_high' => 14.5,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'MPV',
                'name' => 'Mean Platelet Volume',
                'specimen_type' => 'whole blood',
                'price' => 15.00,
                'category' => 'hematology',
                'units' => 'fL',
                'normal_range' => '6.5-12.0',
                'normal_range_low' => 6.5,
                'normal_range_high' => 12.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'NEUT%',
                'name' => 'Neutrophils Percentage',
                'specimen_type' => 'whole blood',
                'price' => 20.00,
                'category' => 'hematology',
                'units' => '%',
                'normal_range' => '45-70',
                'normal_range_low' => 45.0,
                'normal_range_high' => 70.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'LYMPH%',
                'name' => 'Lymphocytes Percentage',
                'specimen_type' => 'whole blood',
                'price' => 20.00,
                'category' => 'hematology',
                'units' => '%',
                'normal_range' => '20-40',
                'normal_range_low' => 20.0,
                'normal_range_high' => 40.0,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            
            // === CBS-40 Electrolyte & Chemistry Tests ===
            [
                'code' => 'NA',
                'name' => 'Sodium',
                'specimen_type' => 'serum',
                'price' => 12.00,
                'category' => 'chemistry',
                'units' => 'mmol/L',
                'normal_range' => '136-145',
                'normal_range_low' => 136.0,
                'normal_range_high' => 145.0,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'K',
                'name' => 'Potassium',
                'specimen_type' => 'serum',
                'price' => 12.00,
                'category' => 'chemistry',
                'units' => 'mmol/L',
                'normal_range' => '3.5-5.1',
                'normal_range_low' => 3.5,
                'normal_range_high' => 5.1,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'CL',
                'name' => 'Chloride',
                'specimen_type' => 'serum',
                'price' => 12.00,
                'category' => 'chemistry',
                'units' => 'mmol/L',
                'normal_range' => '98-107',
                'normal_range_low' => 98.0,
                'normal_range_high' => 107.0,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'CO2',
                'name' => 'Carbon Dioxide',
                'specimen_type' => 'serum',
                'price' => 12.00,
                'category' => 'chemistry',
                'units' => 'mmol/L',
                'normal_range' => '22-29',
                'normal_range_low' => 22.0,
                'normal_range_high' => 29.0,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'BUN',
                'name' => 'Blood Urea Nitrogen',
                'specimen_type' => 'serum',
                'price' => 15.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '7-20',
                'normal_range_low' => 7.0,
                'normal_range_high' => 20.0,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'CREA',
                'name' => 'Creatinine',
                'specimen_type' => 'serum',
                'price' => 15.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => 'M: 0.7-1.3, F: 0.6-1.1',
                'normal_range_low' => 0.6,
                'normal_range_high' => 1.3,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'GLU',
                'name' => 'Glucose',
                'specimen_type' => 'serum',
                'price' => 10.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '70-100 (fasting)',
                'normal_range_low' => 70.0,
                'normal_range_high' => 100.0,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'CA',
                'name' => 'Calcium',
                'specimen_type' => 'serum',
                'price' => 12.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '8.5-10.5',
                'normal_range_low' => 8.5,
                'normal_range_high' => 10.5,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'MG',
                'name' => 'Magnesium',
                'specimen_type' => 'serum',
                'price' => 12.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '1.6-2.6',
                'normal_range_low' => 1.6,
                'normal_range_high' => 2.6,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            
            // === Contec BC300 Biochemistry Tests ===
            [
                'code' => 'ALT',
                'name' => 'Alanine Aminotransferase',
                'specimen_type' => 'serum',
                'price' => 18.00,
                'category' => 'chemistry',
                'units' => 'U/L',
                'normal_range' => 'M: 7-56, F: 7-56',
                'normal_range_low' => 7.0,
                'normal_range_high' => 56.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'AST',
                'name' => 'Aspartate Aminotransferase',
                'specimen_type' => 'serum',
                'price' => 18.00,
                'category' => 'chemistry',
                'units' => 'U/L',
                'normal_range' => 'M: 10-40, F: 10-40',
                'normal_range_low' => 10.0,
                'normal_range_high' => 40.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'ALP',
                'name' => 'Alkaline Phosphatase',
                'specimen_type' => 'serum',
                'price' => 18.00,
                'category' => 'chemistry',
                'units' => 'U/L',
                'normal_range' => '44-147',
                'normal_range_low' => 44.0,
                'normal_range_high' => 147.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'BILI_T',
                'name' => 'Bilirubin, Total',
                'specimen_type' => 'serum',
                'price' => 15.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '0.2-1.2',
                'normal_range_low' => 0.2,
                'normal_range_high' => 1.2,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'BILI_D',
                'name' => 'Bilirubin, Direct',
                'specimen_type' => 'serum',
                'price' => 15.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '0.0-0.3',
                'normal_range_low' => 0.0,
                'normal_range_high' => 0.3,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'CHOL',
                'name' => 'Cholesterol, Total',
                'specimen_type' => 'serum',
                'price' => 20.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '<200',
                'normal_range_low' => null,
                'normal_range_high' => 200.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'HDL',
                'name' => 'HDL Cholesterol',
                'specimen_type' => 'serum',
                'price' => 20.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => 'M: >40, F: >50',
                'normal_range_low' => 40.0,
                'normal_range_high' => null,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'LDL',
                'name' => 'LDL Cholesterol',
                'specimen_type' => 'serum',
                'price' => 20.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '<100',
                'normal_range_low' => null,
                'normal_range_high' => 100.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'TRIG',
                'name' => 'Triglycerides',
                'specimen_type' => 'serum',
                'price' => 18.00,
                'category' => 'chemistry',
                'units' => 'mg/dL',
                'normal_range' => '<150',
                'normal_range_low' => null,
                'normal_range_high' => 150.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'TSH',
                'name' => 'Thyroid Stimulating Hormone',
                'specimen_type' => 'serum',
                'price' => 35.00,
                'category' => 'immunology',
                'units' => 'mIU/L',
                'normal_range' => '0.4-4.0',
                'normal_range_low' => 0.4,
                'normal_range_high' => 4.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'T3',
                'name' => 'Triiodothyronine',
                'specimen_type' => 'serum',
                'price' => 30.00,
                'category' => 'immunology',
                'units' => 'ng/dL',
                'normal_range' => '80-200',
                'normal_range_low' => 80.0,
                'normal_range_high' => 200.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'T4',
                'name' => 'Thyroxine',
                'specimen_type' => 'serum',
                'price' => 30.00,
                'category' => 'immunology',
                'units' => 'μg/dL',
                'normal_range' => '4.5-12.0',
                'normal_range_low' => 4.5,
                'normal_range_high' => 12.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'IRON',
                'name' => 'Iron, Serum',
                'specimen_type' => 'serum',
                'price' => 22.00,
                'category' => 'chemistry',
                'units' => 'μg/dL',
                'normal_range' => 'M: 65-175, F: 50-170',
                'normal_range_low' => 50.0,
                'normal_range_high' => 175.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'FERR',
                'name' => 'Ferritin',
                'specimen_type' => 'serum',
                'price' => 25.00,
                'category' => 'immunology',
                'units' => 'ng/mL',
                'normal_range' => 'M: 12-300, F: 12-150',
                'normal_range_low' => 12.0,
                'normal_range_high' => 300.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'B12',
                'name' => 'Vitamin B12',
                'specimen_type' => 'serum',
                'price' => 28.00,
                'category' => 'immunology',
                'units' => 'pg/mL',
                'normal_range' => '200-900',
                'normal_range_low' => 200.0,
                'normal_range_high' => 900.0,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            
            // === Common Panel Tests ===
            [
                'code' => 'CBC',
                'name' => 'Complete Blood Count',
                'specimen_type' => 'whole blood',
                'price' => 35.00,
                'category' => 'hematology',
                'units' => 'panel',
                'normal_range' => 'See individual components',
                'normal_range_low' => null,
                'normal_range_high' => null,
                'equipment' => 'Mission HA-360',
                'is_active' => true
            ],
            [
                'code' => 'CMP',
                'name' => 'Comprehensive Metabolic Panel',
                'specimen_type' => 'serum',
                'price' => 45.00,
                'category' => 'chemistry',
                'units' => 'panel',
                'normal_range' => 'See individual components',
                'normal_range_low' => null,
                'normal_range_high' => null,
                'equipment' => 'CBS-40',
                'is_active' => true
            ],
            [
                'code' => 'LFT',
                'name' => 'Liver Function Tests',
                'specimen_type' => 'serum',
                'price' => 40.00,
                'category' => 'chemistry',
                'units' => 'panel',
                'normal_range' => 'See individual components',
                'normal_range_low' => null,
                'normal_range_high' => null,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ],
            [
                'code' => 'LIPID',
                'name' => 'Lipid Panel',
                'specimen_type' => 'serum',
                'price' => 38.00,
                'category' => 'chemistry',
                'units' => 'panel',
                'normal_range' => 'See individual components',
                'normal_range_low' => null,
                'normal_range_high' => null,
                'equipment' => 'Contec BC300',
                'is_active' => true
            ]
        ];

        foreach ($labTests as $test) {
            LabTest::updateOrCreate(
                ['code' => $test['code']],
                $test
            );
        }
    }
}
