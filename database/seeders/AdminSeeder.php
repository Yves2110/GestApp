<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id'=>3,
            'service_id'=>3,
            'firstname'=>'DEPS',
            'lastname'=>'DEPS',
            'email'=>'deps@gmail.com',
            'number'=>'70000000',
            'password'=>Hash::make('password'),

        ]);
    }
}
