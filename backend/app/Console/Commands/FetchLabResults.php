<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EquipmentIntegrationService;
use App\Models\LabEquipment;
use Illuminate\Support\Facades\Log;

class FetchLabResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lab:fetch-results {--equipment= : Specific equipment ID to fetch from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically fetch lab results from connected equipment';

    protected EquipmentIntegrationService $equipmentService;

    public function __construct(EquipmentIntegrationService $equipmentService)
    {
        parent::__construct();
        $this->equipmentService = $equipmentService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting lab results fetch process...');

        $equipmentId = $this->option('equipment');

        try {
            if ($equipmentId) {
                $equipment = LabEquipment::findOrFail($equipmentId);
                $this->info("Fetching results from {$equipment->name}...");
                
                $results = $this->equipmentService->fetchResultsFromEquipment($equipment);
                $this->info("Successfully fetched " . count($results) . " results from {$equipment->name}");
                
                Log::info("Manual fetch completed for {$equipment->name}", [
                    'equipment_id' => $equipment->id,
                    'results_count' => count($results)
                ]);
            } else {
                $this->info('Fetching results from all active equipment...');
                
                $allResults = $this->equipmentService->fetchResultsFromAllEquipment();
                $totalResults = 0;
                
                foreach ($allResults as $equipmentName => $results) {
                    if (is_array($results)) {
                        $count = count($results);
                        $totalResults += $count;
                        $this->line("  - {$equipmentName}: {$count} results");
                    } else {
                        $this->error("  - {$equipmentName}: Error - " . ($results['error'] ?? 'Unknown error'));
                    }
                }
                
                $this->info("Total results fetched: {$totalResults}");
                
                Log::info("Automated fetch completed", [
                    'total_results' => $totalResults,
                    'equipment_results' => $allResults
                ]);
            }

            $this->info('Lab results fetch process completed successfully.');
            return 0;

        } catch (\Exception $e) {
            $this->error('Error during fetch process: ' . $e->getMessage());
            Log::error('Lab results fetch failed', [
                'error' => $e->getMessage(),
                'equipment_id' => $equipmentId
            ]);
            return 1;
        }
    }
}
