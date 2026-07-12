<?php

namespace Tests\Feature;

use App\Models\Objective;
use App\Models\UnderObjective;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesTestFixtures;
use Tests\TestCase;

class ObjectiveTest extends TestCase
{
    use RefreshDatabase, CreatesTestFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createFixtures();
    }

    // ── Objectifs ──────────────────────────────────────────

    public function test_guest_cannot_view_objectives(): void
    {
        $this->get(route('Objective'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_objectives(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('Objective'))
            ->assertStatus(200);
    }

    public function test_superadmin_can_create_objective(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('ObjectiveStore'), [
                'label'   => 'Nouvel objectif',
                'role_id' => 3,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('objectives', ['label' => 'Nouvel objectif']);
    }

    public function test_objective_store_requires_label(): void
    {
        $this->actingAs($this->superAdmin)
            ->post(route('ObjectiveStore'), [])
            ->assertSessionHasErrors(['label']);
    }

    public function test_superadmin_can_update_objective(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->put(route('ObjectiveUpdate', $this->objective->id), [
                'label'   => 'Objectif modifié',
                'role_id' => 3,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('objectives', ['id' => $this->objective->id, 'label' => 'Objectif modifié']);
    }

    public function test_superadmin_can_delete_objective(): void
    {
        $objective = Objective::create(['role_id' => 3, 'label' => 'À supprimer']);

        $this->actingAs($this->superAdmin)
            ->get(route('delete.objective', $objective->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('objectives', ['id' => $objective->id]);
    }

    public function test_edit_objective_page_loads(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('edit.objective', $this->objective->id))
            ->assertStatus(200);
    }

    // ── Sous-objectifs ─────────────────────────────────────

    public function test_authenticated_user_can_view_under_objectives(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('Under_Objective'))
            ->assertStatus(200);
    }

    public function test_superadmin_can_create_under_objective(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('UnderObjectiveStore'), [
                'label'        => 'Nouveau sous-objectif',
                'objective_id' => $this->objective->id,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('under_objectives', ['label' => 'Nouveau sous-objectif']);
    }

    public function test_under_objective_store_requires_label_and_objective(): void
    {
        $this->actingAs($this->superAdmin)
            ->post(route('UnderObjectiveStore'), [])
            ->assertSessionHasErrors(['label', 'objective_id']);
    }

    public function test_superadmin_can_delete_under_objective(): void
    {
        $under = UnderObjective::create([
            'objective_id' => $this->objective->id,
            'label'        => 'À supprimer',
        ]);

        $this->actingAs($this->superAdmin)
            ->get(route('delete.under_objective', $under->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('under_objectives', ['id' => $under->id]);
    }
}
