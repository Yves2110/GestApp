<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'serviceRelation'])->orderByDesc('id')->paginate(15);
        $roles = Role::all();
        $services = Service::all();

        return view('pages.settings.users', compact('users', 'roles', 'services'));
    }

    /**
     * Active / désactive un compte utilisateur.
     */
    public function toggleActive($id)
    {
        $user = User::findOrFail($id);

        if ((int) $user->id === (int) Auth::id()) {
            return back()->with('error', "Vous ne pouvez pas désactiver votre propre compte.");
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', "Impossible de désactiver un Super Administrateur.");
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $state = $user->is_active ? 'activé' : 'désactivé';
        AuditLogger::log(
            'user_' . ($user->is_active ? 'activated' : 'deactivated'),
            "Compte {$user->email} {$state}",
            User::class,
            $user->id
        );

        return back()->with('message', "Le compte de {$user->firstname} a été {$state}.");
    }

    /**
     * Réinitialise les identifiants (mot de passe temporaire).
     */
    public function resetCredentials($id)
    {
        $user = User::findOrFail($id);

        $tempPassword = Str::password(12, true, true, false);

        $user->password = Hash::make($tempPassword);
        $user->must_reset_password = true;
        $user->save();

        AuditLogger::log(
            'user_credentials_reset',
            "Réinitialisation des identifiants de {$user->email}",
            User::class,
            $user->id
        );

        return back()
            ->with('message', "Identifiants réinitialisés pour {$user->firstname}.")
            ->with('temp_password', $tempPassword)
            ->with('temp_password_user', $user->email);
    }
}
