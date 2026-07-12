<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Matrice rôles x permissions (SuperAdmin uniquement).
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('group')->orderBy('label')->get()->groupBy('group');

        return view('pages.settings.permissions', compact('roles', 'permissions'));
    }

    /**
     * Met à jour les permissions d'un rôle donné.
     */
    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        // Le rôle SuperAdmin conserve toujours toutes les permissions : non modifiable.
        if ((int) $role->id === 1) {
            return back()->with('error', "Les permissions du Super Administrateur ne sont pas modifiables.");
        }

        $data = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        AuditLogger::log(
            'role_permissions_updated',
            "Permissions du rôle « {$role->label} » mises à jour",
            Role::class,
            $role->id
        );

        return back()->with('message', "Permissions du rôle « {$role->label} » mises à jour.");
    }
}
