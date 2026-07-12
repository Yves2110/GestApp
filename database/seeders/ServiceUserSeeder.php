<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ServiceUserSeeder extends Seeder
{
    /**
     * Utilisateur de type Service (role_id = 4), rattaché au service D.S.I (id 4).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'service@gmail.com'],
            [
                'role_id'    => 4,
                'service_id' => 4,
                'firstname'  => 'Service',
                'lastname'   => 'DSI',
                'number'     => '70000002',
                'password'   => Hash::make('password'),
                'is_active'  => true,
            ]
        );
    }
}
