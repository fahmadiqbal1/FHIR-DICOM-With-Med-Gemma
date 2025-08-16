<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LabEquipment;

class LabEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            [
                'name' => 'Hematology Analyzer - Lab Station 1',
                'model' => 'Mission HA-360',
                'manufacturer' => 'Mission Bio',
                'serial_number' => 'HA360-2024-001',
                'ip_address' => '192.168.1.100',
                'port' => 4001,
                'connection_type' => 'tcp',
                'protocol' => 'astm',
                'is_active' => true,
                'auto_fetch_enabled' => true,
                'backup_method' => 'ocr',
                'configuration' => [
                    'baud_rate' => 9600,
                    'data_bits' => 8,
                    'stop_bits' => 1,
                    'parity' => 'none',
                    'watch_directory' => '/var/lab/mission',
                    'file_pattern' => '*.dat',
                    'auto_archive' => true,
                    'qc_enabled' => true
                ],
                'supported_tests' => [
                    'WBC', 'RBC', 'HGB', 'HCT', 'PLT', 'MCV', 'MCH', 'MCHC',
                    'RDW', 'MPV', 'NEUT%', 'LYMPH%', 'MONO%', 'EOS%', 'BASO%',
                    'NEUT#', 'LYMPH#', 'MONO#', 'EOS#', 'BASO#'
                ]
            ],
            [
                'name' => 'Electrolyte Analyzer - Lab Station 2',
                'model' => 'CBS-40',
                'manufacturer' => 'Caretium Medical Instruments',
                'serial_number' => 'CBS40-2024-002',
                'ip_address' => '192.168.1.101',
                'port' => 4002,
                'connection_type' => 'tcp',
                'protocol' => 'lis',
                'is_active' => true,
                'auto_fetch_enabled' => true,
                'backup_method' => 'ocr',
                'configuration' => [
                    'calibration_frequency' => 'daily',
                    'qc_frequency' => 'every_batch',
                    'watch_directory' => '/var/lab/cbs40',
                    'file_pattern' => '*.csv',
                    'delimiter' => ',',
                    'auto_dilution' => true,
                    'temperature_controlled' => true
                ],
                'supported_tests' => [
                    'NA', 'K', 'CL', 'CO2', 'BUN', 'CREA', 'GLU',
                    'CA', 'MG', 'PHOS', 'URIC', 'ALB', 'TP'
                ]
            ],
            [
                'name' => 'Biochemistry Analyzer - Lab Station 3',
                'model' => 'Contec BC300',
                'manufacturer' => 'Contec Medical Systems',
                'serial_number' => 'BC300-2024-003',
                'ip_address' => '192.168.1.102',
                'port' => 4003,
                'connection_type' => 'file_transfer',
                'protocol' => 'custom',
                'is_active' => true,
                'auto_fetch_enabled' => true,
                'backup_method' => 'ocr',
                'configuration' => [
                    'serial_port' => '/dev/ttyUSB0',
                    'baud_rate' => 9600,
                    'watch_directory' => '/var/lab/contec',
                    'file_pattern' => '*.csv',
                    'header_rows' => 2,
                    'sample_id_column' => 0,
                    'test_code_column' => 1,
                    'result_column' => 2,
                    'units_column' => 3,
                    'reference_range_column' => 4,
                    'auto_calculate_flags' => true
                ],
                'supported_tests' => [
                    'ALT', 'AST', 'ALP', 'GGT', 'BILI_T', 'BILI_D', 'BILI_I',
                    'CHOL', 'HDL', 'LDL', 'TRIG', 'UREA', 'CREA', 'GLU',
                    'ALB', 'TP', 'AMY', 'LIP', 'CK', 'LDH', 'IRON', 'TIBC',
                    'FERR', 'B12', 'FOLATE', 'TSH', 'T3', 'T4', 'PSA'
                ]
            ]
        ];

        foreach ($equipment as $item) {
            LabEquipment::create($item);
        }
    }
}
