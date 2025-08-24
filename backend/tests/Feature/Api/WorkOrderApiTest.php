<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class WorkOrderApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles if they don't exist
        if (!\Spatie\Permission\Models\Role::where('name', 'Admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        }
        
        $this->user = User::factory()->create();
        $this->user->assignRole('Admin');
        $this->supplier = Supplier::factory()->create();
    }

    /** @test */
    public function it_can_create_work_order()
    {
        $workOrderData = [
            'title' => 'Replace CT Scanner',
            'description' => 'Replace the old CT scanner with a new one',
            'supplier_id' => $this->supplier->id,
            'assigned_to' => $this->user->id,
            'priority' => 'high',
            'status' => 'pending',
            'estimated_cost' => 150000.00,
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'location' => 'Radiology Department',
            'category' => 'equipment_replacement'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/work-orders', $workOrderData);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'id',
                'title',
                'description',
                'supplier_id',
                'assigned_to',
                'priority',
                'status',
                'estimated_cost',
                'actual_cost',
                'due_date',
                'completed_at',
                'location',
                'category',
                'notes',
                'created_at',
                'updated_at',
                'supplier',
                'assigned_user'
            ]);

        $this->assertDatabaseHas('work_orders', [
            'title' => 'Replace CT Scanner',
            'supplier_id' => $this->supplier->id,
            'estimated_cost' => 150000.00
        ]);
    }

    /** @test */
    public function it_can_list_work_orders()
    {
        WorkOrder::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'supplier_id' => $this->supplier->id,
            'assigned_to' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/work-orders');

        $response->assertOk()
            ->assertJsonCount(5)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'status',
                    'priority',
                    'estimated_cost',
                    'due_date',
                    'supplier',
                    'assignedUser'
                ]
            ]);
    }

    /** @test */
    public function it_can_show_specific_work_order()
    {
        $workOrder = WorkOrder::factory()->create([
            'supplier_id' => $this->supplier->id,
            'assigned_to' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/work-orders/{$workOrder->id}");

        $response->assertOk()
            ->assertJson([
                'id' => $workOrder->id,
                'title' => $workOrder->title,
                'status' => $workOrder->status
            ])
            ->assertJsonStructure([
                'supplier' => ['id', 'name'],
                'assigned_user' => ['id', 'name']
            ]);
    }

    /** @test */
    public function it_can_update_work_order()
    {
        $workOrder = WorkOrder::factory()->create([
            'title' => 'Original Title',
            'status' => 'pending',
            'actual_cost' => null
        ]);

        $updateData = [
            'title' => 'Updated Work Order Title',
            'status' => 'in_progress',
            'actual_cost' => 125000.00,
            'notes' => 'Work started on schedule'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/work-orders/{$workOrder->id}", $updateData);

        $response->assertOk()
            ->assertJson([
                'message' => 'Work order updated successfully',
                'work_order' => [
                    'title' => 'Updated Work Order Title',
                    'status' => 'in_progress',
                    'actual_cost' => '125000.00'
                ]
            ]);

        $this->assertDatabaseHas('work_orders', [
            'id' => $workOrder->id,
            'title' => 'Updated Work Order Title',
            'status' => 'in_progress',
            'actual_cost' => 125000.00
        ]);
    }

    /** @test */
    public function it_sets_completed_at_when_status_changed_to_completed()
    {
        $workOrder = WorkOrder::factory()->create(['status' => 'in_progress']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/work-orders/{$workOrder->id}", [
                'status' => 'completed'
            ]);

        $response->assertOk();
        $this->assertNotNull($workOrder->fresh()->completed_at);
    }

    /** @test */
    public function it_can_delete_work_order()
    {
        $workOrder = WorkOrder::factory()->pending()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/work-orders/{$workOrder->id}");

        $response->assertOk()
            ->assertJson(['message' => 'Work order deleted successfully']);

        $this->assertDatabaseMissing('work_orders', ['id' => $workOrder->id]);
    }

    /** @test */
    public function it_can_filter_work_orders_by_status()
    {
        // Clear any existing work orders for this user
        WorkOrder::where('user_id', $this->user->id)->delete();
        
        WorkOrder::factory()->create([
            'status' => 'pending',
            'user_id' => $this->user->id
        ]);
        WorkOrder::factory()->create([
            'status' => 'completed',
            'user_id' => $this->user->id
        ]);
        WorkOrder::factory()->create([
            'status' => 'pending',
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/work-orders?status=pending');

        $response->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_filter_work_orders_by_priority()
    {
        // Clear any existing work orders for this user
        WorkOrder::where('user_id', $this->user->id)->delete();
        
        WorkOrder::factory()->create([
            'priority' => 'high',
            'user_id' => $this->user->id
        ]);
        WorkOrder::factory()->create([
            'priority' => 'low',
            'user_id' => $this->user->id
        ]);
        WorkOrder::factory()->create([
            'priority' => 'high',
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/work-orders?priority=high');

        $response->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_filter_work_orders_by_supplier()
    {
        // Clear any existing work orders for this user
        WorkOrder::where('user_id', $this->user->id)->delete();
        
        $supplier2 = Supplier::factory()->create();
        
        WorkOrder::factory()->create([
            'supplier_id' => $this->supplier->id,
            'user_id' => $this->user->id
        ]);
        WorkOrder::factory()->create([
            'supplier_id' => $supplier2->id,
            'user_id' => $this->user->id
        ]);
        WorkOrder::factory()->create([
            'supplier_id' => $this->supplier->id,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/work-orders?supplier_id={$this->supplier->id}");

        $response->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_work_order()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/work-orders', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'description']);
    }

    /** @test */
    public function it_validates_supplier_exists()
    {
        $workOrderData = [
            'title' => 'Test Work Order',
            'description' => 'Test description',
            'supplier_id' => 99999 // Non-existent supplier
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/work-orders', $workOrderData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['supplier_id']);
    }

    /** @test */
    public function it_validates_assigned_user_exists()
    {
        $workOrderData = [
            'title' => 'Test Work Order',
            'description' => 'Test description',
            'assigned_to' => 99999 // Non-existent user
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/work-orders', $workOrderData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['assigned_to']);
    }

    /** @test */
    public function it_can_get_work_order_statistics()
    {
        WorkOrder::factory()->create([
            'status' => 'pending',
            'user_id' => $this->user->id,
            'estimated_cost' => 1000
        ]);
        WorkOrder::factory()->create([
            'status' => 'in_progress',
            'user_id' => $this->user->id,
            'estimated_cost' => 2000
        ]);
        WorkOrder::factory()->create([
            'status' => 'completed',
            'user_id' => $this->user->id,
            'estimated_cost' => 1500,
            'actual_cost' => 1400
        ]);
        WorkOrder::factory()->create([
            'status' => 'cancelled',
            'user_id' => $this->user->id,
            'estimated_cost' => 500
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/work-orders/statistics/overview');

        $response->assertOk()
            ->assertJsonStructure([
                'total',
                'by_status',
                'by_priority',
                'total_estimated_cost',
                'total_actual_cost',
                'overdue_count'
            ]);
    }
}
