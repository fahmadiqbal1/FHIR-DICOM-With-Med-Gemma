<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SupplierApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles if they don't exist
        if (!\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        }
        
        $this->user = User::factory()->create();
        $this->user->assignRole('admin');
    }

    /** @test */
    public function it_can_create_supplier()
    {
        $supplierData = [
            'name' => 'Medical Supplies Inc.',
            'type' => 'medical_equipment',
            'contact_person' => 'John Smith',
            'email' => 'john@medicalsupplies.com',
            'phone' => '+1-555-0123',
            'address' => '123 Healthcare Ave',
            'city' => 'New York',
            'country' => 'USA',
            'payment_terms' => 'Net 30',
            'notes' => 'Preferred supplier for X-ray equipment'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/suppliers', $supplierData);

        $response->assertCreated()
            ->assertJsonStructure([
                'id',
                'name',
                'type',
                'contact_person',
                'email',
                'phone',
                'address',
                'city',
                'country',
                'payment_terms',
                'notes'
            ]);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Medical Supplies Inc.',
            'email' => 'john@medicalsupplies.com',
            'type' => 'medical_equipment'
        ]);
    }

    /** @test */
    public function it_can_list_suppliers()
    {
        Supplier::factory()->count(5)->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/suppliers');

        $response->assertOk()
            ->assertJsonCount(5)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'contact_person',
                    'email',
                    'phone',
                    'status',
                    'category'
                ]
            ]);
    }

    /** @test */
    public function it_can_show_specific_supplier()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/suppliers/{$supplier->id}");

        $response->assertOk()
            ->assertJson([
                'id' => $supplier->id,
                'name' => $supplier->name,
                'email' => $supplier->email
            ]);
    }

    /** @test */
    public function it_can_update_supplier()
    {
        $supplier = Supplier::factory()->create([
            'name' => 'Original Name',
            'status' => 'active'
        ]);

        $updateData = [
            'name' => 'Updated Medical Supplies',
            'status' => 'inactive'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/suppliers/{$supplier->id}", $updateData);

        $response->assertOk()
            ->assertJson([
                'name' => 'Updated Medical Supplies',
                'status' => 'inactive'
            ]);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Updated Medical Supplies',
            'status' => 'inactive'
        ]);
    }

    /** @test */
    public function it_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create();
        
        // Ensure no work orders exist for this supplier
        $this->assertEquals(0, $supplier->workOrders()->count());

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/suppliers/{$supplier->id}");

        $response->assertOk()
            ->assertJson(['message' => 'Supplier deleted successfully']);

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    /** @test */
    public function it_can_filter_suppliers_by_category()
    {
        Supplier::factory()->create(['category' => 'medical_equipment']);
        Supplier::factory()->create(['category' => 'pharmaceuticals']);
        Supplier::factory()->create(['category' => 'medical_equipment']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/suppliers?category=medical_equipment');

        $response->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_can_filter_suppliers_by_status()
    {
        Supplier::factory()->create(['status' => 'active']);
        Supplier::factory()->create(['status' => 'inactive']);
        Supplier::factory()->create(['status' => 'active']);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/suppliers?status=active');

        $response->assertOk()
            ->assertJsonCount(2);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_supplier()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/suppliers', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $supplierData = [
            'name' => 'Test Supplier',
            'email' => 'invalid-email'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/suppliers', $supplierData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_prevents_duplicate_email()
    {
        Supplier::factory()->create(['email' => 'test@supplier.com']);

        $supplierData = [
            'name' => 'Another Supplier',
            'email' => 'test@supplier.com'
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/suppliers', $supplierData);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_can_get_supplier_work_orders()
    {
        $supplier = Supplier::factory()->create();
        WorkOrder::factory()->count(3)->create(['supplier_id' => $supplier->id]);
        WorkOrder::factory()->count(2)->create(); // Different supplier

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/suppliers/{$supplier->id}/work-orders");

        $response->assertOk()
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'created_at'
                ]
            ]);
    }
}
