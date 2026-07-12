<?php

namespace Database\Seeders;

use App\Models\Objective;
use Illuminate\Database\Seeder;

class ObjectiveSeeder extends Seeder
{
    public function run(): void
    {
        $objectives = [
            ['role_id' => 1, 'label' => 'Gestion globale de la plateforme'],
            ['role_id' => 2, 'label' => 'Pilotage strategique institutionnel'],
            ['role_id' => 2, 'label' => 'Relations avec les partenaires'],
            ['role_id' => 3, 'label' => 'Administration operationnelle'],
            ['role_id' => 3, 'label' => 'Suivi budgetaire et reporting'],
            ['role_id' => 4, 'label' => 'Formation et capacitation'],
            ['role_id' => 4, 'label' => 'Infrastructure numerique'],
            ['role_id' => 4, 'label' => 'Appui logistique et technique'],
        ];

        foreach ($objectives as $objective) {
            Objective::updateOrCreate(
                ['label' => $objective['label']],
                $objective
            );
        }
    }
}
