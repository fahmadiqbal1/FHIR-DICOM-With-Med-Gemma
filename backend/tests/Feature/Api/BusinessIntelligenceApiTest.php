<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\Invoice;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class BusinessIntelligenceApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles if they don't exist
        if (!\Spatie\Permission\Models\Role::where('name', 'owner')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'owner']);
        }
        
        $this->user = User::factory()->create();
        $this->user->assignRole('owner');
        
        // Create test data
        $this->createTestData();
    }

    protected function createTestData()
    {
        // Create suppliers
        $supplier1 = Supplier::factory()->create(['name' => 'Medical Equipment Co.']);
        $supplier2 = Supplier::factory()->create(['name' => 'Pharmaceutical Supplies']);

        // Create work orders with expenses
        WorkOrder::factory()->create([
            'supplier_id' => $supplier1->id,
            'status' => 'completed',
            'estimated_cost' => 10000,
            'actual_cost' => 12000,
            'category' => 'equipment_maintenance',
            'created_at' => Carbon::now()->subDays(10)
        ]);

        WorkOrder::factory()->create([
            'supplier_id' => $supplier2->id,
            'status' => 'completed',
            'estimated_cost' => 5000,
            'actual_cost' => 4800,
            'category' => 'pharmaceuticals',
            'created_at' => Carbon::now()->subDays(5)
        ]);

        WorkOrder::factory()->create([
            'supplier_id' => $supplier1->id,
            'status' => 'in_progress',
            'estimated_cost' => 15000,
            'actual_cost' => null,
            'category' => 'equipment_replacement',
            'created_at' => Carbon::now()->subDays(2)
        ]);

        // Create invoices for income
        Invoice::factory()->create([
            'total_amount' => 2500.00,
            'status' => 'paid',
            'created_at' => Carbon::now()->subDays(8)
        ]);

        Invoice::factory()->create([
            'total_amount' => 3200.00,
            'status' => 'paid',
            'created_at' => Carbon::now()->subDays(3)
        ]);

        Invoice::factory()->create([
            'total_amount' => 1800.00,
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(1)
        ]);
    }

    /** @test */
    public function it_can_get_business_intelligence_data()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/business-intelligence');

        $response->assertOk()
            ->assertJsonStructure([
                'income' => [
                    'total_revenue',
                    'paid_invoices',
                    'pending_invoices',
                    'monthly_breakdown'
                ],
                'expenses' => [
                    'total_expenses',
                    'completed_work_orders',
                    'pending_work_orders',
                    'category_breakdown'
                ],
                'profit_loss' => [
                    'net_profit',
                    'profit_margin',
                    'break_even_point'
                ],
                'trends' => [
                    'income_trend',
                    'expense_trend',
                    'profit_trend'
                ]
            ]);

        // Verify calculated values
        $data = $response->json();
        
        // Total revenue should be sum of paid invoices (2500 + 3200 = 5700)
        $this->assertEquals(5700, $data['income']['total_revenue']);
        
        // Total expenses should be sum of actual costs (12000 + 4800 = 16800)
        $this->assertEquals(16800, $data['expenses']['total_expenses']);
        
        // Net profit should be revenue - expenses (5700 - 16800 = -11100)
        $this->assertEquals(-11100, $data['profit_loss']['net_profit']);
    }

    /** @test */
    public function it_can_get_expense_tracking_data()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/business-intelligence/expenses');

        $response->assertOk()
            ->assertJsonStructure([
                'total_expenses',
                'completed_work_orders',
                'pending_work_orders',
                'category_breakdown' => [
                    '*' => [
                        'category',
                        'total_cost',
                        'count'
                    ]
                ],
                'supplier_breakdown' => [
                    '*' => [
                        'supplier_name',
                        'total_cost',
                        'work_order_count'
                    ]
                ],
                'monthly_expenses'
            ]);

        $data = $response->json();
        
        // Verify expense calculations
        $this->assertEquals(16800, $data['total_expenses']);
        $this->assertEquals(2, $data['completed_work_orders']);
        $this->assertEquals(1, $data['pending_work_orders']);
        
        // Verify category breakdown
        $categories = collect($data['category_breakdown']);
        $equipmentMaintenance = $categories->where('category', 'equipment_maintenance')->first();
        $this->assertEquals(12000, $equipmentMaintenance['total_cost']);
    }

    /** @test */
    public function it_can_get_income_vs_expenses_analysis()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/business-intelligence/income-vs-expenses');

        $response->assertOk()
            ->assertJsonStructure([
                'period_summary' => [
                    'total_income',
                    'total_expenses',
                    'net_result',
                    'profit_margin'
                ],
                'monthly_comparison',
                'department_performance',
                'cost_efficiency_ratio',
                'recommendations'
            ]);

        $data = $response->json();
        
        // Verify calculations
        $this->assertEquals(5700, $data['period_summary']['total_income']);
        $this->assertEquals(16800, $data['period_summary']['total_expenses']);
        $this->assertEquals(-11100, $data['period_summary']['net_result']);
        
        // Verify recommendations are provided
        $this->assertIsArray($data['recommendations']);
        $this->assertNotEmpty($data['recommendations']);
    }

    /** @test */
    public function it_can_generate_ai_insights()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/business-intelligence/ai-insights', [
                'period' => 'last_30_days',
                'focus_areas' => ['expenses', 'profitability']
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'insights' => [
                    'expense_analysis',
                    'profitability_analysis',
                    'trend_analysis',
                    'efficiency_metrics'
                ],
                'recommendations' => [
                    'immediate_actions',
                    'strategic_improvements',
                    'cost_optimization'
                ],
                'kpi_summary' => [
                    'revenue_growth',
                    'cost_reduction_potential',
                    'efficiency_score'
                ],
                'generated_at'
            ]);

        $data = $response->json();
        
        // Verify AI insights structure
        $this->assertIsArray($data['insights']);
        $this->assertIsArray($data['recommendations']);
        $this->assertArrayHasKey('immediate_actions', $data['recommendations']);
        $this->assertArrayHasKey('strategic_improvements', $data['recommendations']);
        $this->assertArrayHasKey('cost_optimization', $data['recommendations']);
    }

    /** @test */
    public function it_can_export_business_report()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/business-intelligence/export-report', [
                'format' => 'json',
                'period' => 'last_30_days',
                'include_charts' => false
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'report_data' => [
                    'period',
                    'summary',
                    'income_analysis',
                    'expense_analysis',
                    'profitability',
                    'recommendations'
                ],
                'metadata' => [
                    'generated_at',
                    'generated_by',
                    'period_covered',
                    'data_points'
                ]
            ]);

        $data = $response->json();
        
        // Verify report structure
        $this->assertArrayHasKey('summary', $data['report_data']);
        $this->assertArrayHasKey('income_analysis', $data['report_data']);
        $this->assertArrayHasKey('expense_analysis', $data['report_data']);
        $this->assertEquals($this->user->name, $data['metadata']['generated_by']);
    }

    /** @test */
    public function it_can_filter_data_by_date_range()
    {
        $startDate = Carbon::now()->subDays(7)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/business-intelligence?start_date={$startDate}&end_date={$endDate}");

        $response->assertOk();
        
        $data = $response->json();
        
        // Should only include data from the last 7 days
        // This would exclude the work order from 10 days ago and invoice from 8 days ago
        $this->assertLessThan(16800, $data['expenses']['total_expenses']);
    }

    /** @test */
    public function it_requires_owner_role_for_business_intelligence()
    {
        $regularUser = User::factory()->create();

        $response = $this->actingAs($regularUser, 'sanctum')
            ->getJson('/api/business-intelligence');

        $response->assertForbidden();
    }

    /** @test */
    public function it_can_get_department_performance_metrics()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/business-intelligence/department-performance');

        $response->assertOk()
            ->assertJsonStructure([
                'departments' => [
                    '*' => [
                        'name',
                        'revenue',
                        'expenses',
                        'profit_margin',
                        'efficiency_score',
                        'work_order_count'
                    ]
                ],
                'top_performing_department',
                'improvement_opportunities'
            ]);

        $data = $response->json();
        
        // Verify departments are analyzed
        $this->assertIsArray($data['departments']);
        $this->assertNotEmpty($data['departments']);
    }

    /** @test */
    public function it_validates_date_range_parameters()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/business-intelligence?start_date=invalid-date');

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['start_date']);
    }

    /** @test */
    public function it_validates_export_format_parameter()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/business-intelligence/export-report', [
                'format' => 'invalid_format'
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['format']);
    }
}
