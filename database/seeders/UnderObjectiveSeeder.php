<?php

namespace Database\Seeders;

use App\Models\Objective;
use App\Models\under_objective;
use Illuminate\Database\Seeder;

class UnderObjectiveSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'Gestion globale de la plateforme' => [
                'Superviser les acces utilisateurs',
                'Maintenir la securite systeme',
                'Auditer les operations cles',
            ],
            'Pilotage strategique institutionnel' => [
                'Definir les orientations annuelles',
                'Valider le plan d\'actions',
            ],
            'Relations avec les partenaires' => [
                'Mobiliser les ressources externes',
                'Signaler les conventions de partenariat',
            ],
            'Administration operationnelle' => [
                'Gerer les ressources humaines',
                'Coordonner les services',
            ],
            'Suivi budgetaire et reporting' => [
                'Etablir les previsions financieres',
                'Produire les rapports trimestriels',
            ],
            'Formation et capacitation' => [
                'Organiser les sessions de formation',
                'Evaluer les competences acquises',
            ],
            'Infrastructure numerique' => [
                'Deployer les equipements reseau',
                'Assurer la maintenance logicielle',
            ],
            'Appui logistique et technique' => [
                'Fournir le materiel de bureau',
                'Gerer les prestations externes',
            ],
        ];

        foreach ($mapping as $objectiveLabel => $labels) {
            $objective = Objective::where('label', $objectiveLabel)->first();

            if (! $objective) {
                continue;
            }

            foreach ($labels as $label) {
                under_objective::updateOrCreate(
                    ['objective_id' => $objective->id, 'label' => $label],
                    ['objective_id' => $objective->id, 'label' => $label]
                );
            }
        }
    }
}
