<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestFixtures;
use Tests\TestCase;

class PdfExportTest extends TestCase
{
    use RefreshDatabase, CreatesTestFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createFixtures();
    }

    public function test_guest_cannot_export_activities_pdf(): void
    {
        $this->get(route('export.pdf.activities'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_export_performance_pdf(): void
    {
        $this->get(route('export.pdf.performance'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_download_activities_pdf(): void
    {
        $this->makeActivity(['label' => 'Activité PDF Test']);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('export.pdf.activities'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_authenticated_user_can_download_performance_pdf(): void
    {
        $this->makeActivity(['workflow_status' => 'validated']);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('export.pdf.performance'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_activities_pdf_filename_contains_date(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('export.pdf.activities'));

        $disposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('rapport-activites-', $disposition);
        $this->assertStringContainsString('.pdf', $disposition);
    }

    public function test_activities_pdf_filters_by_service(): void
    {
        $this->makeActivity(['label' => 'Act Service 1']);
        $this->makeActivity(['service_id' => $this->otherService->id, 'label' => 'Act Autre']);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('export.pdf.activities', ['service_id' => $this->service->id]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_service_user_pdf_scoped_to_own_service(): void
    {
        $this->makeActivity(['label' => 'Ma PDF']);
        $this->makeActivity(['service_id' => $this->otherService->id, 'label' => 'Autre PDF']);

        $response = $this->actingAs($this->serviceUser)
            ->get(route('export.pdf.activities'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
