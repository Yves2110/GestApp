<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
        RoleSeeder::class,
        ServiceSeeder::class,
        AdminSeeder::class,
        SuperAdminSeeder::class,
        PresidentSeeder::class,
        PeriodeSeeder::class,
        PermissionSeeder::class,
        ServiceUserSeeder::class,
        ObjectiveSeeder::class,
        UnderObjectiveSeeder::class,
        ActivitySeeder::class,
       ]);
    }
}
