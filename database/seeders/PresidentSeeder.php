<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PresidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id'=>2,
            'firstname'=>'President',
            'lastname'=>'President',
            'email'=>'president@gmail.com',
            'tel'=>'70000001',
            'sub'=>'President',
            'birthday'=>'',
            'password'=>Hash::make('password'),

        ]);
    }
}
