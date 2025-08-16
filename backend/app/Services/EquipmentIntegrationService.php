<?php

namespace App\Services;

use App\Models\LabEquipment;
use App\Models\LabOrder;
use App\Models\LabResult;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class EquipmentIntegrationService
{
    protected array $equipmentHandlers = [
        'Mission HA-360' => 'handleMissionHA360',
        'CBS-40' => 'handleCBS40',
        'Contec BC300' => 'handleContecBC300'
    ];

    public function fetchResultsFromAllEquipment(): array
    {
        $results = [];
        $activeEquipment = LabEquipment::where('is_active', true)
            ->where('auto_fetch_enabled', true)
            ->get();

        foreach ($activeEquipment as $equipment) {
            try {
                $equipmentResults = $this->fetchResultsFromEquipment($equipment);
                $results[$equipment->name] = $equipmentResults;
            } catch (Exception $e) {
                Log::error("Failed to fetch results from {$equipment->name}: " . $e->getMessage());
                $results[$equipment->name] = ['error' => $e->getMessage()];
            }
        }

        return $results;
    }

    public function fetchResultsFromEquipment(LabEquipment $equipment): array
    {
        $handlerMethod = $this->equipmentHandlers[$equipment->model] ?? null;
        
        if (!$handlerMethod || !method_exists($this, $handlerMethod)) {
            throw new Exception("No handler found for equipment model: {$equipment->model}");
        }

        // Update last connected timestamp
        $equipment->update(['last_connected_at' => now()]);

        return $this->$handlerMethod($equipment);
    }

    protected function handleMissionHA360(LabEquipment $equipment): array
    {
        // Mission HA-360 3-diff Automatic Hematology Analyser
        // Typically uses ASTM protocol or serial communication
        
        $results = [];
        $config = $equipment->configuration ?? [];
        
        switch ($equipment->connection_type) {
            case 'tcp':
                $results = $this->fetchViaTCP($equipment, [
                    'commands' => ['QUERY RESULTS', 'GET PENDING'],
                    'response_format' => 'astm'
                ]);
                break;
                
            case 'serial':
                $results = $this->fetchViaSerial($equipment, [
                    'baud_rate' => $config['baud_rate'] ?? 9600,
                    'data_bits' => $config['data_bits'] ?? 8,
                    'stop_bits' => $config['stop_bits'] ?? 1,
                    'parity' => $config['parity'] ?? 'none'
                ]);
                break;
                
            case 'file_transfer':
                $results = $this->fetchViaFileTransfer($equipment, [
                    'watch_directory' => $config['watch_directory'] ?? '/var/lab/mission',
                    'file_pattern' => '*.dat'
                ]);
                break;
        }

        return $this->parseHematologyResults($results, $equipment);
    }

    protected function handleCBS40(LabEquipment $equipment): array
    {
        // CBS-40 Electrolyte Analyser
        // Usually supports direct TCP/IP communication
        
        $results = [];
        $config = $equipment->configuration ?? [];
        
        switch ($equipment->connection_type) {
            case 'tcp':
                $results = $this->fetchViaTCP($equipment, [
                    'commands' => ['GET_RESULTS', 'STATUS'],
                    'response_format' => 'json'
                ]);
                break;
                
            case 'file_transfer':
                $results = $this->fetchViaFileTransfer($equipment, [
                    'watch_directory' => $config['watch_directory'] ?? '/var/lab/cbs40',
                    'file_pattern' => '*.csv'
                ]);
                break;
        }

        return $this->parseElectrolyteResults($results, $equipment);
    }

    protected function handleContecBC300(LabEquipment $equipment): array
    {
        // Contec BC300 Semi-auto bio chemistry analyser
        // Often uses USB or serial communication with CSV export
        
        $results = [];
        $config = $equipment->configuration ?? [];
        
        switch ($equipment->connection_type) {
            case 'file_transfer':
                $results = $this->fetchViaFileTransfer($equipment, [
                    'watch_directory' => $config['watch_directory'] ?? '/var/lab/contec',
                    'file_pattern' => '*.csv'
                ]);
                break;
                
            case 'serial':
                $results = $this->fetchViaSerial($equipment, [
                    'baud_rate' => $config['baud_rate'] ?? 9600
                ]);
                break;
        }

        return $this->parseBiochemistryResults($results, $equipment);
    }

    protected function fetchViaTCP(LabEquipment $equipment, array $options): array
    {
        $results = [];
        
        try {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $connection = socket_connect($socket, $equipment->ip_address, $equipment->port);
            
            if (!$connection) {
                throw new Exception("Cannot connect to {$equipment->ip_address}:{$equipment->port}");
            }

            foreach ($options['commands'] as $command) {
                socket_write($socket, $command . "\r\n");
                $response = socket_read($socket, 2048);
                $results[] = ['command' => $command, 'response' => $response];
            }

            socket_close($socket);
        } catch (Exception $e) {
            Log::error("TCP connection error for {$equipment->name}: " . $e->getMessage());
            throw $e;
        }

        return $results;
    }

    protected function fetchViaSerial(LabEquipment $equipment, array $options): array
    {
        // This would require a serial communication library
        // For demonstration, we'll simulate the connection
        $results = [];
        
        $config = $equipment->configuration ?? [];
        $port = $config['serial_port'] ?? '/dev/ttyUSB0';
        
        // In real implementation, you'd use a library like ReactPHP Serial or similar
        Log::info("Simulating serial connection to {$port} for {$equipment->name}");
        
        // Simulate response
        $results[] = [
            'timestamp' => now(),
            'data' => 'Sample serial data from ' . $equipment->name
        ];

        return $results;
    }

    protected function fetchViaFileTransfer(LabEquipment $equipment, array $options): array
    {
        $results = [];
        $watchDir = $options['watch_directory'];
        $pattern = $options['file_pattern'];
        
        if (!is_dir($watchDir)) {
            Log::warning("Watch directory does not exist: {$watchDir}");
            return $results;
        }

        $files = glob($watchDir . '/' . $pattern);
        
        foreach ($files as $file) {
            $fileTime = filemtime($file);
            $cutoffTime = now()->subMinutes(5)->timestamp;
            
            // Only process files modified in the last 5 minutes
            if ($fileTime > $cutoffTime) {
                $content = file_get_contents($file);
                $results[] = [
                    'file' => $file,
                    'content' => $content,
                    'modified_at' => date('Y-m-d H:i:s', $fileTime)
                ];
                
                // Move processed file to archive
                $archiveDir = $watchDir . '/processed';
                if (!is_dir($archiveDir)) {
                    mkdir($archiveDir, 0755, true);
                }
                rename($file, $archiveDir . '/' . basename($file));
            }
        }

        return $results;
    }

    protected function parseHematologyResults(array $rawResults, LabEquipment $equipment): array
    {
        $parsedResults = [];
        
        foreach ($rawResults as $result) {
            $data = $result['response'] ?? $result['content'] ?? '';
            
            // Parse ASTM or custom format for hematology results
            $lines = explode("\n", $data);
            
            foreach ($lines as $line) {
                if (preg_match('/^R\|(\d+)\|([A-Z0-9]+)\|([^|]+)\|([^|]*)\|([^|]*)\|/', $line, $matches)) {
                    $parsedResults[] = [
                        'test_code' => $matches[2],
                        'test_name' => $matches[3],
                        'result_value' => $matches[4],
                        'units' => $matches[5] ?? '',
                        'timestamp' => now(),
                        'raw_data' => $line
                    ];
                }
            }
        }

        return $this->processResults($parsedResults, $equipment);
    }

    protected function parseElectrolyteResults(array $rawResults, LabEquipment $equipment): array
    {
        $parsedResults = [];
        
        foreach ($rawResults as $result) {
            $data = $result['response'] ?? $result['content'] ?? '';
            
            // Parse CSV or JSON format for electrolyte results
            if (isset($result['file']) && str_ends_with($result['file'], '.csv')) {
                $lines = str_getcsv($data, "\n");
                $headers = str_getcsv(array_shift($lines));
                
                foreach ($lines as $line) {
                    $values = str_getcsv($line);
                    if (count($values) >= count($headers)) {
                        $parsedResults[] = [
                            'test_code' => $values[1] ?? '',
                            'test_name' => $values[2] ?? '',
                            'result_value' => $values[3] ?? '',
                            'units' => $values[4] ?? '',
                            'timestamp' => now(),
                            'raw_data' => $line
                        ];
                    }
                }
            }
        }

        return $this->processResults($parsedResults, $equipment);
    }

    protected function parseBiochemistryResults(array $rawResults, LabEquipment $equipment): array
    {
        $parsedResults = [];
        
        foreach ($rawResults as $result) {
            $data = $result['response'] ?? $result['content'] ?? '';
            
            // Parse biochemistry results format
            if (isset($result['file']) && str_ends_with($result['file'], '.csv')) {
                $lines = str_getcsv($data, "\n");
                
                foreach ($lines as $line) {
                    $values = str_getcsv($line);
                    if (count($values) >= 4) {
                        $parsedResults[] = [
                            'sample_id' => $values[0] ?? '',
                            'test_code' => $values[1] ?? '',
                            'result_value' => $values[2] ?? '',
                            'units' => $values[3] ?? '',
                            'timestamp' => now(),
                            'raw_data' => $line
                        ];
                    }
                }
            }
        }

        return $this->processResults($parsedResults, $equipment);
    }

    protected function processResults(array $parsedResults, LabEquipment $equipment): array
    {
        $processedResults = [];
        
        foreach ($parsedResults as $result) {
            // Find matching lab order
            $labOrder = $this->findMatchingLabOrder($result, $equipment);
            
            if ($labOrder) {
                $labResult = LabResult::create([
                    'lab_order_id' => $labOrder->id,
                    'equipment_id' => $equipment->id,
                    'test_code' => $result['test_code'],
                    'test_name' => $result['test_name'] ?? $result['test_code'],
                    'result_value' => $result['result_value'],
                    'result_units' => $result['units'] ?? '',
                    'result_status' => 'preliminary',
                    'performed_at' => $result['timestamp'],
                    'source_type' => 'equipment',
                    'raw_data' => $result,
                    'quality_control_passed' => true
                ]);

                // Update lab order status
                $labOrder->update([
                    'status' => 'resulted',
                    'resulted_at' => now(),
                    'result_value' => $result['result_value']
                ]);

                $processedResults[] = $labResult;
            }
        }

        return $processedResults;
    }

    protected function findMatchingLabOrder(array $result, LabEquipment $equipment): ?LabOrder
    {
        // Try to match by sample ID or test code
        $sampleId = $result['sample_id'] ?? null;
        $testCode = $result['test_code'] ?? null;

        if ($sampleId) {
            $labOrder = LabOrder::where('id', $sampleId)
                ->where('status', 'collected')
                ->first();
            if ($labOrder) return $labOrder;
        }

        if ($testCode) {
            $labOrder = LabOrder::whereHas('test', function($query) use ($testCode) {
                $query->where('code', $testCode);
            })
            ->where('status', 'collected')
            ->orderBy('collected_at', 'desc')
            ->first();
            
            if ($labOrder) return $labOrder;
        }

        return null;
    }
}
