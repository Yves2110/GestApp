<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Activités
            ['name' => 'activities.view',   'label' => 'Voir les activites',       'group' => 'Activites'],
            ['name' => 'activities.create', 'label' => 'Creer une activite',       'group' => 'Activites'],
            ['name' => 'activities.update', 'label' => 'Modifier une activite',    'group' => 'Activites'],
            ['name' => 'activities.delete', 'label' => 'Supprimer une activite',   'group' => 'Activites'],

            // Objectifs
            ['name' => 'objectives.view',   'label' => 'Voir les objectifs',       'group' => 'Objectifs'],
            ['name' => 'objectives.create', 'label' => 'Creer un objectif',        'group' => 'Objectifs'],
            ['name' => 'objectives.update', 'label' => 'Modifier un objectif',     'group' => 'Objectifs'],
            ['name' => 'objectives.delete', 'label' => 'Supprimer un objectif',    'group' => 'Objectifs'],

            // Services
            ['name' => 'services.view',     'label' => 'Voir les services',        'group' => 'Services'],
            ['name' => 'services.manage',   'label' => 'Gerer les services',       'group' => 'Services'],

            // Analytique & exports
            ['name' => 'analytics.view',    'label' => "Acceder a l'analytique",   'group' => 'Analytique'],
            ['name' => 'exports.manage',    'label' => 'Exporter les donnees',     'group' => 'Analytique'],

            // Guides
            ['name' => 'guides.view',       'label' => 'Voir les guides',          'group' => 'Guides'],
            ['name' => 'guides.manage',     'label' => 'Gerer les guides',         'group' => 'Guides'],

            // Administration
            ['name' => 'users.manage',      'label' => 'Gerer les utilisateurs',   'group' => 'Administration'],
            ['name' => 'audit.view',        'label' => "Consulter l'audit",        'group' => 'Administration'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }

        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        $allPermissionIds = Permission::pluck('id', 'name')->toArray();

        $president = role::where('label', 'president')->first();
        $admin = role::where('label', 'admin')->first();
        $service = role::where('label', 'services')->first();

        if ($president) {
            $president->permissions()->sync([
                $allPermissionIds['activities.view'],
                $allPermissionIds['objectives.view'],
                $allPermissionIds['services.view'],
                $allPermissionIds['analytics.view'],
                $allPermissionIds['exports.manage'],
                $allPermissionIds['guides.view'],
            ]);
        }

        if ($admin) {
            $admin->permissions()->sync([
                $allPermissionIds['activities.view'],
                $allPermissionIds['activities.create'],
                $allPermissionIds['activities.update'],
                $allPermissionIds['activities.delete'],
                $allPermissionIds['objectives.view'],
                $allPermissionIds['objectives.create'],
                $allPermissionIds['objectives.update'],
                $allPermissionIds['objectives.delete'],
                $allPermissionIds['services.view'],
                $allPermissionIds['guides.view'],
                $allPermissionIds['guides.manage'],
                $allPermissionIds['exports.manage'],
            ]);
        }

        if ($service) {
            $service->permissions()->sync([
                $allPermissionIds['activities.view'],
                $allPermissionIds['activities.create'],
                $allPermissionIds['activities.update'],
                $allPermissionIds['guides.view'],
            ]);
        }
    }
}
