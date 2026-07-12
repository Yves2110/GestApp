<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        $stats = [
            'users'       => User::count(),
            'active_users'=> User::where('is_active', true)->count(),
            'roles'       => Role::count(),
            'permissions' => Permission::count(),
            'audit_logs'  => AuditLog::count(),
        ];

        return view('pages.settings.index', compact('stats'));
    }
}
