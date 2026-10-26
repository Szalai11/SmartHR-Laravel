<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offre;
use App\Models\EtapeRecrutement;

class OffreSeeder extends Seeder
{
    public function run(): void
    {
        $offres = [
            [
                'titre' => 'Développeur Full Stack Laravel',
                'description' => 'Nous recherchons un développeur Full Stack maîtrisant Laravel, PHP, MySQL, JavaScript, HTML/CSS. Bon relationnel et travail en équipe requis.',
                'date_publication' => now(),
                'lieu' => 'Lomé, Togo',
                'type_contrat' => 'CDI',
                'statut' => 'publiee',
            ],
            [
                'titre' => 'Responsable Ressources Humaines',
                'description' => 'Nous recherchons un responsable RH pour gérer le recrutement, la formation, les relations sociales et la paie. Expérience exigée.',
                'date_publication' => now(),
                'lieu' => 'Lomé, Togo',
                'type_contrat' => 'CDI',
                'statut' => 'publiee',
            ],
            [
                'titre' => 'Infographiste 3D',
                'description' => 'Création de visuels 3D, maîtrise de Blender, Maya ou 3ds Max. Portfolio exigé.',
                'date_publication' => now(),
                'lieu' => 'Lomé, Togo',
                'type_contrat' => 'CDD',
                'statut' => 'publiee',
            ],
            [
                'titre' => 'Chef de Projet Digital',
                'description' => 'Pilotage de projets digitaux, gestion d\'équipe, relation client. Expérience en agence web appréciée.',
                'date_publication' => now(),
                'lieu' => 'Lomé, Togo',
                'type_contrat' => 'CDI',
                'statut' => 'publiee',
            ],
        ];

        foreach ($offres as $offreData) {
            $offre = Offre::create($offreData);
            
            // 🔥 AJOUTER LES ÉTAPES POUR CHAQUE OFFRE
            $etapes = ['Présélection', 'Test technique', 'Entretien RH', 'Entretien technique', 'Validation finale'];
            foreach ($etapes as $index => $nom) {
                EtapeRecrutement::create([
                    'offre_id' => $offre->id,
                    'nom_etape' => $nom,
                    'ordre' => $index + 1,
                ]);
            }
        }
    }
}