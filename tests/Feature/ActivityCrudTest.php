<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Periode;
use App\Models\Role;
use App\Models\Service;
use App\Models\UnderObjective;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ActivityCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $serviceUser;
    protected array $activityData;

    protected function setUp(): void
    {
        parent::setUp();

        Role::insert([
            ['label' => 'SuperAdmin'],
            ['label' => 'president'],
            ['label' => 'admin'],
            ['label' => 'services'],
        ]);

        $service = Service::create(['label' => 'Service Test']);
        $objective = Objective::create(['role_id' => 3, 'label' => 'Objectif Test']);
        $underObjective = UnderObjective::create(['objective_id' => $objective->id, 'label' => 'Sous-Objectif Test']);
        $periode = Periode::create(['label' => 'T1 2025']);

        $this->superAdmin = User::create([
            'role_id'    => 1,
            'service_id' => $service->id,
            'firstname'  => 'Super',
            'lastname'   => 'Admin',
            'email'      => 'superadmin@test.com',
            'number'     => '00000001',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $this->serviceUser = User::create([
            'role_id'    => 4,
            'service_id' => $service->id,
            'firstname'  => 'Service',
            'lastname'   => 'User',
            'email'      => 'service@test.com',
            'number'     => '00000002',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $this->activityData = [
            'service_id'         => $service->id,
            'objective_id'       => $objective->id,
            'under_objective_id' => $underObjective->id,
            'periode_id'         => $periode->id,
            'label'              => 'Activité de test',
            'indicator'          => 'Indicateur test',
            'target'             => 'Cible test',
            'price'              => 500000,
            'source_of_funding'  => 'Budget national',
            'structure'          => 'Direction',
            'commentary'         => null,
        ];
    }

    public function test_guest_cannot_access_activities_list(): void
    {
        $response = $this->get(route('Activites'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_activities_list(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('Activites'));
        $response->assertStatus(200);
    }

    public function test_superadmin_can_create_activity(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('ActivitiesStore'), $this->activityData);

        $response->assertRedirect();
        $this->assertDatabaseHas('activities', ['label' => 'Activité de test']);
    }

    public function test_activity_store_fails_without_required_fields(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('ActivitiesStore'), []);

        $response->assertSessionHasErrors(['service_id', 'objective_id', 'under_objective_id', 'periode_id', 'label']);
    }

    public function test_service_user_can_only_see_own_service_activities(): void
    {
        Activity::create(array_merge($this->activityData, ['status' => 0]));

        $otherService = Service::create(['label' => 'Autre Service']);
        Activity::create(array_merge($this->activityData, [
            'service_id' => $otherService->id,
            'label'      => 'Activité autre service',
            'status'     => 0,
        ]));

        $response = $this->actingAs($this->serviceUser)->get(route('Activites'));
        $response->assertStatus(200);
        $response->assertSee('Activité de test');
        $response->assertDontSee('Activité autre service');
    }

    public function test_superadmin_can_delete_activity(): void
    {
        $activity = Activity::create(array_merge($this->activityData, ['status' => 0]));

        $response = $this->actingAs($this->superAdmin)
            ->delete(route('activites.destroy', $activity->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }

    public function test_service_user_cannot_delete_other_service_activity(): void
    {
        $otherService = Service::create(['label' => 'Autre Service']);
        $activity = Activity::create(array_merge($this->activityData, [
            'service_id' => $otherService->id,
            'status'     => 0,
        ]));

        $response = $this->actingAs($this->serviceUser)
            ->delete(route('activites.destroy', $activity->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    public function test_superadmin_can_update_activity(): void
    {
        $activity = Activity::create(array_merge($this->activityData, ['status' => 0]));

        $updatedData = array_merge($this->activityData, ['label' => 'Activité modifiée']);

        $response = $this->actingAs($this->superAdmin)
            ->put(route('activites.update', $activity->id), $updatedData);

        $response->assertRedirect(route('Activites'));
        $this->assertDatabaseHas('activities', ['id' => $activity->id, 'label' => 'Activité modifiée']);
    }
}
