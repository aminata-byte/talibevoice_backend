<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrateur
        User::firstOrCreate(
            ['email' => 'admin@talibevoice.sn'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telephone' => '+221 33 000 00 00',
                'statut' => 'actif',
            ],
        );

        // Agents de terrain
        User::firstOrCreate(
            ['email' => 'moussa@talibevoice.sn'],
            [
                'name' => 'Moussa Diallo',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'telephone' => '+221 77 000 00 01',
                'matricule' => 'AGT001',
                'zone_affectation' => 'Dakar',
                'statut' => 'actif',
            ],
        );

        User::firstOrCreate(
            ['email' => 'fatou@talibevoice.sn'],
            [
                'name' => 'Fatou Ndiaye',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'telephone' => '+221 77 000 00 02',
                'matricule' => 'AGT002',
                'zone_affectation' => 'Thiès',
                'statut' => 'actif',
            ],
        );
    }
}
