<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
    }

    /** @test */
    public function it_can_retrieve_user_notifications()
    {
        // Create test notifications
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'type' => 'lab_request',
            'read_at' => null
        ]);

        Notification::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'type' => 'work_order',
            'read_at' => now()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/notifications');

        $response->assertOk()
            ->assertJsonStructure([
                'notifications' => [
                    '*' => [
                        'id',
                        'type',
                        'title',
                        'message',
                        'priority',
                        'is_read',
                        'created_at'
                    ]
                ],
                'unread_count'
            ]);
            
        $this->assertEquals(5, count($response->json('notifications')));
    }

    /** @test */
    public function it_can_mark_notification_as_read()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id,
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/notifications/{$notification->id}/read");

        $response->assertOk()
            ->assertJson(['message' => 'Notification marked as read']);

        $this->assertNotNull($notification->fresh()->read_at);
    }

    /** @test */
    public function it_can_mark_all_notifications_as_read()
    {
        Notification::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'read_at' => null
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson('/api/notifications/read-all');

        $response->assertOk()
            ->assertJson(['message' => 'All notifications marked as read']);

        $this->assertEquals(0, Notification::where('user_id', $this->user->id)
            ->whereNull('read_at')->count());
    }

    /** @test */
    public function it_can_get_notification_counts()
    {
        // Create unread notifications
        Notification::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'read_at' => null
        ]);

        // Create read notifications
        Notification::factory()->count(2)->create([
            'user_id' => $this->user->id,
            'read_at' => now()
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/notifications/counts');

        $response->assertOk()
            ->assertJsonStructure([
                'unread',
                'total'
            ])
            ->assertJson([
                'unread' => 3,
                'total' => 5
            ]);
    }

    /** @test */
    public function it_can_delete_notification()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/notifications/{$notification->id}");

        $response->assertOk()
            ->assertJson(['message' => 'Notification deleted']);

        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    /** @test */
    public function it_prevents_unauthorized_access_to_other_user_notifications()
    {
        $otherUser = User::factory()->create();
        $notification = Notification::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/notifications/{$notification->id}/read");

        $response->assertForbidden();
    }

    /** @test */
    public function it_creates_notification_for_lab_request()
    {
        $labOrderData = [
            'patient_name' => 'John Doe',
            'test_type' => 'Blood Test',
            'priority' => 'urgent'
        ];

        $notification = Notification::createLabRequest($this->user->id, $labOrderData);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->user->id,
            'type' => 'lab_request',
            'priority' => 'urgent'
        ]);

        $this->assertStringContainsString('John Doe', $notification->message);
        $this->assertStringContainsString('Blood Test', $notification->message);
    }

    /** @test */
    public function it_creates_notification_for_work_order()
    {
        $workOrderData = (object)[
            'id' => 123,
            'title' => 'Equipment Maintenance',
            'description' => 'Routine maintenance of CT scanner',
            'priority' => 'normal',
            'supplier' => (object)['name' => 'Medical Supplies Co.']
        ];

        $notification = Notification::createWorkOrder($this->user->id, $workOrderData);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->user->id,
            'type' => 'work_order',
            'priority' => 'normal'
        ]);

        $this->assertStringContainsString('Work Order #123', $notification->title);
        $this->assertStringContainsString('Equipment Maintenance', $notification->message);
    }
}
