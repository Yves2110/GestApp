<?php

namespace Database\Seeders;

use App\Models\Periode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trimestres=['Trimestre 1','Trimestre 2','Trimestre 3','Trimestre 4'];
        foreach($trimestres as $trimestre){
            Periode::create([
                'label'=>$trimestre,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);
        }
    }
}
