<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestFixtures;
use Tests\TestCase;

class CalendarTimelineTest extends TestCase
{
    use RefreshDatabase, CreatesTestFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createFixtures();
    }

    // ── Calendrier ─────────────────────────────────────────

    public function test_guest_cannot_access_calendar(): void
    {
        $this->get(route('activites.calendar'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_calendar(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('activites.calendar'))
            ->assertStatus(200);
    }

    public function test_calendar_api_returns_json(): void
    {
        $this->makeActivity();

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.calendar.events'));

        $response->assertStatus(200)
                 ->assertJsonIsArray();
    }

    public function test_calendar_api_returns_event_structure(): void
    {
        $this->makeActivity(['label' => 'Activité Calendrier']);

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.calendar.events'));

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Activité Calendrier'])
                 ->assertJsonStructure([['id', 'title', 'start', 'backgroundColor', 'extendedProps']]);
    }

    public function test_calendar_api_filters_by_service(): void
    {
        $this->makeActivity(['label' => 'Activité Service 1']);
        $this->makeActivity(['service_id' => $this->otherService->id, 'label' => 'Activité Service 2']);

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.calendar.events', ['service_id' => $this->service->id]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Activité Service 1']);

        $data = $response->json();
        $titles = array_column($data, 'title');
        $this->assertNotContains('Activité Service 2', $titles);
    }

    public function test_calendar_api_filters_by_workflow_status(): void
    {
        $this->makeActivity(['workflow_status' => 'validated', 'label' => 'Validée']);
        $this->makeActivity(['workflow_status' => 'draft',     'label' => 'Brouillon']);

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.calendar.events', ['workflow_status' => 'validated']));

        $data   = $response->json();
        $titles = array_column($data, 'title');
        $this->assertContains('Validée', $titles);
        $this->assertNotContains('Brouillon', $titles);
    }

    public function test_service_user_only_sees_own_service_in_calendar(): void
    {
        $this->makeActivity(['label' => 'Ma activité']);
        $this->makeActivity(['service_id' => $this->otherService->id, 'label' => 'Autre service']);

        $response = $this->actingAs($this->serviceUser)
            ->getJson(route('api.calendar.events'));

        $data   = $response->json();
        $titles = array_column($data, 'title');
        $this->assertContains('Ma activité', $titles);
        $this->assertNotContains('Autre service', $titles);
    }

    // ── Timeline ───────────────────────────────────────────

    public function test_guest_cannot_access_timeline(): void
    {
        $this->get(route('activites.timeline'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_timeline(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('activites.timeline'))
            ->assertStatus(200);
    }

    public function test_timeline_api_returns_json(): void
    {
        $this->makeActivity();

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.timeline.data'));

        $response->assertStatus(200)
                 ->assertJsonStructure(['groups', 'min_date', 'max_date']);
    }

    public function test_timeline_api_groups_by_service(): void
    {
        $this->makeActivity(['label' => 'Act 1']);
        $this->makeActivity([
            'service_id' => $this->otherService->id,
            'label'      => 'Act 2',
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.timeline.data'));

        $data = $response->json();
        $this->assertCount(2, $data['groups']);
    }

    public function test_timeline_api_filters_by_workflow(): void
    {
        $this->makeActivity(['workflow_status' => 'validated', 'label' => 'Validée TL']);
        $this->makeActivity(['workflow_status' => 'draft',     'label' => 'Brouillon TL']);

        $response = $this->actingAs($this->superAdmin)
            ->getJson(route('api.timeline.data', ['workflow_status' => 'validated']));

        $data   = $response->json();
        $labels = collect($data['groups'])->flatMap(fn($g) => collect($g['items'])->pluck('label'))->toArray();
        $this->assertContains('Validée TL', $labels);
        $this->assertNotContains('Brouillon TL', $labels);
    }

    public function test_service_user_only_sees_own_service_in_timeline(): void
    {
        $this->makeActivity(['label' => 'Ma TL']);
        $this->makeActivity(['service_id' => $this->otherService->id, 'label' => 'Autre TL']);

        $response = $this->actingAs($this->serviceUser)
            ->getJson(route('api.timeline.data'));

        $data   = $response->json();
        $labels = collect($data['groups'])->flatMap(fn($g) => collect($g['items'])->pluck('label'))->toArray();
        $this->assertContains('Ma TL', $labels);
        $this->assertNotContains('Autre TL', $labels);
    }
}
