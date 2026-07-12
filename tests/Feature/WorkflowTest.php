<?php

namespace Tests\Feature;

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestFixtures;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase, CreatesTestFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createFixtures();
    }

    public function test_activity_starts_as_draft(): void
    {
        $activity = $this->makeActivity();
        $this->assertEquals('draft', $activity->workflow_status);
    }

    public function test_service_user_can_submit_own_activity(): void
    {
        $activity = $this->makeActivity();

        $response = $this->actingAs($this->serviceUser)
            ->post(route('activites.workflow', $activity->id), ['to_status' => 'pending']);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'id'              => $activity->id,
            'workflow_status' => 'pending',
        ]);
    }

    public function test_admin_can_validate_pending_activity(): void
    {
        $activity = $this->makeActivity(['workflow_status' => 'pending']);

        $response = $this->actingAs($this->adminUser)
            ->post(route('activites.workflow', $activity->id), ['to_status' => 'validated']);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'id'              => $activity->id,
            'workflow_status' => 'validated',
        ]);
    }

    public function test_admin_can_reject_pending_activity_with_reason(): void
    {
        $activity = $this->makeActivity(['workflow_status' => 'pending']);

        $response = $this->actingAs($this->adminUser)
            ->post(route('activites.workflow', $activity->id), [
                'to_status'        => 'rejected',
                'rejection_reason' => 'Budget insuffisant',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', [
            'id'              => $activity->id,
            'workflow_status' => 'rejected',
        ]);
    }

    public function test_reject_fails_without_reason(): void
    {
        $activity = $this->makeActivity(['workflow_status' => 'pending']);

        $response = $this->actingAs($this->adminUser)
            ->post(route('activites.workflow', $activity->id), [
                'to_status' => 'rejected',
            ]);

        $response->assertSessionHasErrors(['rejection_reason']);
        $this->assertDatabaseHas('activities', [
            'id'              => $activity->id,
            'workflow_status' => 'pending',
        ]);
    }

    public function test_service_user_cannot_validate_activity(): void
    {
        $activity = $this->makeActivity(['workflow_status' => 'pending']);

        $response = $this->actingAs($this->serviceUser)
            ->post(route('activites.workflow', $activity->id), ['to_status' => 'validated']);

        $response->assertStatus(403);
        $this->assertDatabaseHas('activities', [
            'id'              => $activity->id,
            'workflow_status' => 'pending',
        ]);
    }

    public function test_invalid_transition_is_rejected(): void
    {
        $activity = $this->makeActivity(['workflow_status' => 'draft']);

        $response = $this->actingAs($this->adminUser)
            ->post(route('activites.workflow', $activity->id), ['to_status' => 'validated']);

        $response->assertSessionHasErrors(['workflow']);
        $this->assertDatabaseHas('activities', [
            'id'              => $activity->id,
            'workflow_status' => 'draft',
        ]);
    }

    public function test_workflow_history_is_recorded(): void
    {
        $activity = $this->makeActivity();

        $this->actingAs($this->serviceUser)
            ->post(route('activites.workflow', $activity->id), ['to_status' => 'pending']);

        $this->assertDatabaseHas('activity_status_history', [
            'activity_id' => $activity->id,
            'from_status' => 'draft',
            'to_status'   => 'pending',
        ]);
    }

    public function test_guest_cannot_transition_workflow(): void
    {
        $activity = $this->makeActivity();

        $response = $this->post(route('activites.workflow', $activity->id), ['to_status' => 'pending']);
        $response->assertRedirect(route('login'));
    }
}
