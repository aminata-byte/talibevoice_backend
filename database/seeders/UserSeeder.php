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
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@talibevoice.sn',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '+221 33 000 00 00',
            'statut' => 'actif',
        ]);

        // Agents de terrain
        User::create([
            'name' => 'Moussa Diallo',
            'email' => 'moussa@talibevoice.sn',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'telephone' => '+221 77 000 00 01',
            'matricule' => 'AGT001',
            'zone_affectation' => 'Dakar',
            'statut' => 'actif',
        ]);

        User::create([
            'name' => 'Fatou Ndiaye',
            'email' => 'fatou@talibevoice.sn',
            'password' => Hash::make('password'),
            'role' => 'agent',
            'telephone' => '+221 77 000 00 02',
            'matricule' => 'AGT002',
            'zone_affectation' => 'Thiès',
            'statut' => 'actif',
        ]);
    }
}
