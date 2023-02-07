<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id'=>1,
            'service_id'=>1,
            'firstname'=>'KABORE',
            'lastname'=>'Ismael Yves',
            'email'=>'ismaelyveskabore@gmail.com',
            'number'=>'77634303',
            'password'=>Hash::make('password'),

        ]);
    }
}
