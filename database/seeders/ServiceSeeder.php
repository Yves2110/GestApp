<?php

namespace Database\Seeders;

use App\Models\service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobs=['Developper','PRESIDENT','D.E.P.S','D.S.I','D.A.F'];
        foreach($jobs as $job){
            service::create([
                'label'=>$job,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }
    }
}
