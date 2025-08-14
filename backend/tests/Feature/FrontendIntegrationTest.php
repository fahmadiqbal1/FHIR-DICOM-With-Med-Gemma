<?php

namespace Tests\Feature;

use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_route_renders(): void
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $resp = $this->get('/app');
        $resp->assertStatus(200);
        $resp->assertSee('FHIR');
    }

    public function test_medgemma_status_endpoint_returns_json(): void
    {
        $resp = $this->get('/integrations/medgemma');
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['name','integrated','enabled','configured','model']);
    }

    public function test_reports_patients_endpoint_works(): void
    {
        // Create a sample patient
        Patient::factory()->create();

        $resp = $this->get('/reports/patients', ['Accept' => 'application/json']);
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['data']);
    }

    public function test_reports_patient_show_endpoint_works(): void
    {
        $patient = Patient::factory()->create();

        $resp = $this->get('/reports/patients/'.$patient->id, ['Accept' => 'application/json']);
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['id','uuid','mrn','first_name','last_name','sex','imaging_studies','lab_orders','prescriptions','clinical_notes']);
    }
}
