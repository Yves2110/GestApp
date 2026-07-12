<?php

namespace Tests;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Periode;
use App\Models\Role;
use App\Models\Service;
use App\Models\UnderObjective;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait CreatesTestFixtures
{
    protected Service $service;
    protected Service $otherService;
    protected Objective $objective;
    protected UnderObjective $underObjective;
    protected Periode $periode;
    protected User $superAdmin;
    protected User $adminUser;
    protected User $serviceUser;
    protected array $activityData;

    protected function createFixtures(): void
    {
        Role::insert([
            ['label' => 'SuperAdmin'],
            ['label' => 'president'],
            ['label' => 'admin'],
            ['label' => 'services'],
        ]);

        $this->service       = Service::create(['label' => 'Service Principal']);
        $this->otherService  = Service::create(['label' => 'Autre Service']);
        $this->objective     = Objective::create(['role_id' => 3, 'label' => 'Objectif Test']);
        $this->underObjective = UnderObjective::create([
            'objective_id' => $this->objective->id,
            'label'        => 'Sous-Objectif Test',
        ]);
        $this->periode = Periode::create(['label' => 'T1 2025']);

        $this->superAdmin = User::create([
            'role_id'    => 1,
            'service_id' => $this->service->id,
            'firstname'  => 'Super',
            'lastname'   => 'Admin',
            'email'      => 'superadmin@test.com',
            'number'     => '00000001',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $this->adminUser = User::create([
            'role_id'    => 3,
            'service_id' => $this->service->id,
            'firstname'  => 'Admin',
            'lastname'   => 'User',
            'email'      => 'admin@test.com',
            'number'     => '00000003',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $this->serviceUser = User::create([
            'role_id'    => 4,
            'service_id' => $this->service->id,
            'firstname'  => 'Service',
            'lastname'   => 'User',
            'email'      => 'service@test.com',
            'number'     => '00000002',
            'password'   => Hash::make('password'),
            'is_active'  => true,
        ]);

        $this->activityData = [
            'service_id'         => $this->service->id,
            'objective_id'       => $this->objective->id,
            'under_objective_id' => $this->underObjective->id,
            'periode_id'         => $this->periode->id,
            'label'              => 'Activité de test',
            'indicator'          => 'Indicateur test',
            'target'             => 'Cible test',
            'price'              => 500000,
            'source_of_funding'  => 'Budget national',
            'structure'          => 'Direction',
            'status'             => 0,
            'workflow_status'    => 'draft',
        ];
    }

    protected function makeActivity(array $overrides = []): Activity
    {
        return Activity::create(array_merge($this->activityData, $overrides));
    }
}
