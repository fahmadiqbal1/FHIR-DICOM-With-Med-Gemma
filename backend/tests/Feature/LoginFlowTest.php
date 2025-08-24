<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginFlowTest extends TestCase
{
    public function test_owner_login_redirect()
    {
        $owner = User::where('email', 'owner@medgemma.com')->first();
        
        $response = $this->post('/login', [
            'email' => 'owner@medgemma.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
        
        // Follow the redirect
        $response = $this->get('/dashboard');
        
        // Should redirect to owner dashboard
        $response->assertOk();
    }

    public function test_doctor_login_redirect()
    {
        $response = $this->post('/login', [
            'email' => 'doctor1@medgemma.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_lab_tech_login_redirect()
    {
        $response = $this->post('/login', [
            'email' => 'labtech@medgemma.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
    }
}
