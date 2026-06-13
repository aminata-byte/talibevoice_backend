<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Daara;
use App\Models\Talibe;
use App\Models\Besoin;

class DaaraSeeder extends Seeder
{
    public function run(): void
    {
        $daaras = [
            [
                'nom' => 'Daara Al Nour',
                'adresse' => 'Dakar Plateau, Dakar',
                'capacite_accueil' => 100,
                'nombre_talibes' => 74,
                'nom_responsable' => 'Serigne Modou Fall',
                'telephone_responsable' => '+221 77 111 11 11',
                'statut' => 'actif',
                'region' => 'Dakar',
                'commune' => 'Dakar Plateau',
                'latitude' => 14.6937,
                'longitude' => -17.4441,
            ],
            [
                'nom' => 'Darou Salam',
                'adresse' => 'Mbour, Thiès',
                'capacite_accueil' => 150,
                'nombre_talibes' => 112,
                'nom_responsable' => 'Serigne Ibrahima Diop',
                'telephone_responsable' => '+221 77 222 22 22',
                'statut' => 'actif',
                'region' => 'Thiès',
                'commune' => 'Mbour',
                'latitude' => 14.3850,
                'longitude' => -16.9650,
            ],
            [
                'nom' => 'Complex Touba',
                'adresse' => 'Darou Mousty, Louga',
                'capacite_accueil' => 300,
                'nombre_talibes' => 245,
                'nom_responsable' => 'Serigne Cheikh Mbacké',
                'telephone_responsable' => '+221 77 333 33 33',
                'statut' => 'actif',
                'region' => 'Louga',
                'commune' => 'Darou Mousty',
                'latitude' => 15.0667,
                'longitude' => -15.9333,
            ],
            [
                'nom' => 'Daara Malika',
                'adresse' => 'Malika, Dakar',
                'capacite_accueil' => 120,
                'nombre_talibes' => 89,
                'nom_responsable' => 'Serigne Abdoulaye Sy',
                'telephone_responsable' => '+221 77 444 44 44',
                'statut' => 'actif',
                'region' => 'Dakar',
                'commune' => 'Malika',
                'latitude' => 14.7500,
                'longitude' => -17.3000,
            ],
            [
                'nom' => 'Daara Tivaouane',
                'adresse' => 'Tivaouane, Thiès',
                'capacite_accueil' => 200,
                'nombre_talibes' => 134,
                'nom_responsable' => 'Serigne Babacar Thiam',
                'telephone_responsable' => '+221 77 555 55 55',
                'statut' => 'actif',
                'region' => 'Thiès',
                'commune' => 'Tivaouane',
                'latitude' => 14.9500,
                'longitude' => -16.8167,
            ],
            [
                'nom' => 'Daara Saint-Louis',
                'adresse' => 'Saint-Louis',
                'capacite_accueil' => 80,
                'nombre_talibes' => 67,
                'nom_responsable' => 'Serigne Mamadou Kane',
                'telephone_responsable' => '+221 77 666 66 66',
                'statut' => 'actif',
                'region' => 'Saint-Louis',
                'commune' => 'Saint-Louis',
                'latitude' => 16.0179,
                'longitude' => -16.4896,
            ],
        ];

        foreach ($daaras as $daaraData) {
            $daara = Daara::create($daaraData);

            // Ajouter des besoins
            $besoins = [
                [
                    'type' => 'Alimentaire',
                    'description' => '50 sacs de riz',
                    'priorite' => 'urgent',
                    'statut' => 'en_attente',
                    'date_signalement' => now(),
                ],
                [
                    'type' => 'Éducatif',
                    'description' => '30 kits scolaires',
                    'priorite' => 'normal',
                    'statut' => 'en_attente',
                    'date_signalement' => now(),
                ],
            ];

            foreach ($besoins as $besoin) {
                Besoin::create(array_merge($besoin, ['daara_id' => $daara->id]));
            }
        }
    }
}
