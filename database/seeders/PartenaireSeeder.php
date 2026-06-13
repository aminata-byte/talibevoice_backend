<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partenaire;

class PartenaireSeeder extends Seeder
{
    public function run(): void
    {
        $partenaires = [
            [
                'nom' => 'École Polytechnique de Dakar',
                'domaine' => 'Formation',
                'nom_contact' => 'Mamadou Diallo',
                'email' => 'contact@epd.sn',
                'telephone' => '+221 33 821 00 00',
                'site_web' => 'www.epd.sn',
                'code_partenaire' => 'EPD-2024-X1',
                'message_motivation' => 'Nous souhaitons contribuer à la formation professionnelle des talibés.',
                'statut' => 'valide',
            ],
            [
                'nom' => 'Sonatel Academy',
                'domaine' => 'Emploi',
                'nom_contact' => 'Aissatou Ndiaye',
                'email' => 'contact@sonatel-academy.sn',
                'telephone' => '+221 33 839 00 00',
                'site_web' => 'www.sonatel-academy.sn',
                'code_partenaire' => 'SON-2024-X2',
                'message_motivation' => 'Nous offrons des opportunités d\'emploi dans le secteur numérique.',
                'statut' => 'valide',
            ],
            [
                'nom' => 'ONG Espoir Sénégal',
                'domaine' => 'Stage',
                'nom_contact' => 'Cheikh Mbaye',
                'email' => 'contact@espoir-senegal.org',
                'telephone' => '+221 77 123 45 67',
                'site_web' => 'www.espoir-senegal.org',
                'code_partenaire' => 'ESP-2024-X3',
                'message_motivation' => 'Notre ONG s\'engage pour l\'insertion socioprofessionnelle des jeunes.',
                'statut' => 'valide',
            ],
        ];

        foreach ($partenaires as $partenaire) {
            Partenaire::create($partenaire);
        }
    }
}
