<?php

namespace Database\Seeders;

use App\Models\Activities;
use App\Models\Objective;
use App\Models\Periode;
use App\Models\service;
use App\Models\Tdr;
use App\Models\Rapport;
use App\Models\Final_status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $services = service::all();
        $objectives = Objective::with('under_objective')->get();
        $periodes = Periode::all();

        $labelsByService = [
            1 => ['Developpement du module emploi du temps', 'Maintenance corrective du portail web', 'Integration de nouvelles APIs'],
            2 => ['Session du conseil d\'administration', 'Point d\'etape avec les partenaires', 'Validation du plan strategique'],
            3 => ['Organisation du concours d\'entree', 'Gestion des dossiers du personnel', 'Coordination des commissions'],
            4 => ['Deploiement du reseau securise', 'Mise a jour du pare-feu', 'Support utilisateurs internes'],
            5 => ['Acquisition du materiel de bureau', 'Preparation du budget annuel', 'Paiement des prestataires'],
        ];

        $indicators = ['Nombre de sessions', 'Taux de realisation', 'Nombre d\'equipements', 'Nombre de participants', 'Delai de traitement'];
        $targets = ['100%', '50 unites', '20 personnes', '3 mois', '95% de disponibilite'];
        $sources = ['Budget de l\'etablissement', 'Partenaire technique', 'Fonds propres', 'Subvention externe'];
        $structures = ['Direction generale', 'Service informatique', 'Departement administratif', 'Cellule financiere'];
        $commentaries = [
            'Activite planifiee pour le trimestre en cours, en attente de validation.',
            'Realisation conforme au calendrier initial.',
            'Quelques retards constates en raison de contraintes logistiques.',
            'Objectif atteint avec un leger depassement budgetaire.',
        ];

        $statuses = [0, 1];

        foreach ($services as $service) {
            $labels = $labelsByService[$service->id] ?? $labelsByService[3];
            $labelIndex = 0;

            foreach ($periodes as $periode) {
                foreach ($objectives as $objective) {
                    $underObjective = $objective->under_objective->first();

                    if (! $underObjective) {
                        continue;
                    }

                    $label = $labels[$labelIndex % count($labels)];
                    $labelIndex++;

                    $activity = Activities::updateOrCreate(
                        [
                            'service_id' => $service->id,
                            'objective_id' => $objective->id,
                            'under_objective_id' => $underObjective->id,
                            'periode_id' => $periode->id,
                            'label' => $label,
                        ],
                        [
                            'indicator' => $indicators[array_rand($indicators)],
                            'target' => $targets[array_rand($targets)],
                            'price' => rand(100000, 5000000),
                            'source_of_funding' => $sources[array_rand($sources)],
                            'structure' => $structures[array_rand($structures)],
                            'status' => $statuses[array_rand($statuses)],
                            'commentary' => $commentaries[array_rand($commentaries)],
                        ]
                    );

                    $this->seedRelatedRecords($activity);
                }
            }
        }
    }

    private function seedRelatedRecords(Activities $activity): void
    {
        // TDR associe a l'activite
        Tdr::updateOrCreate(
            ['activity_id' => $activity->id],
            [
                'role_id' => 4,
                'fichier' => 'tdr_activite_' . $activity->id . '.pdf',
            ]
        );

        // Variables d'activite (insert direct pour eviter les incoherences du modele)
        $exists = DB::table('activity_variables')->where('activity_id', $activity->id)->exists();

        if (! $exists) {
            DB::table('activity_variables')->insert([
                'activity_id' => $activity->id,
                'number_of_participants' => rand(5, 80),
                'number_of_trainer' => rand(1, 10),
                'number_of_days' => rand(1, 10),
                'place' => collect(['Amphi A', 'Salle de reunion', 'En ligne', 'Campus sud'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Rapport pour certaines activites
        if (rand(1, 100) <= 60) {
            Rapport::updateOrCreate(
                ['activity_id' => $activity->id],
                [
                    'objective_id' => $activity->objective_id,
                    'under_objective_id' => $activity->under_objective_id,
                    'fichier' => 'rapport_activite_' . $activity->id . '.pdf',
                    'number' => 'RAP-' . str_pad((string) $activity->id, 4, '0', STR_PAD_LEFT),
                ]
            );
        }

        // Statut final pour certaines activites
        if (rand(1, 100) <= 40) {
            Final_status::updateOrCreate(
                ['activity_id' => $activity->id],
                [
                    'label' => collect(['Marche conclu', 'En cours', 'Annule', 'Reporte'])->random(),
                    'market_number' => 'MARCHE-' . strtoupper(substr(uniqid(), -6)),
                ]
            );
        }
    }
}
