<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employe;
use Illuminate\Support\Facades\Hash;

class EmployeSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'utilisateur RH
        $userRH = User::create([
            'name' => 'Marie Laurent',
            'email' => 'rh@newbrain.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // RH
        ]);

        Employe::create([
            'user_id' => $userRH->id,
            'matricule' => 'EMP-2024-001',
            'nom' => 'Laurent',
            'prenom' => 'Marie',
            'poste_actuel' => 'Responsable RH',
            'service' => 'Ressources Humaines',
            'date_embauche' => '2020-01-15',
            'solde_conge' => 30,
        ]);

        // Créer l'utilisateur Chef
        $userChef = User::create([
            'name' => 'Thomas Dubois',
            'email' => 'chef@newbrain.com',
            'password' => Hash::make('password123'),
            'role_id' => 3, // Chef
        ]);

        Employe::create([
            'user_id' => $userChef->id,
            'matricule' => 'EMP-2024-002',
            'nom' => 'Dubois',
            'prenom' => 'Thomas',
            'poste_actuel' => 'Chef de service IT',
            'service' => 'Informatique',
            'date_embauche' => '2019-06-10',
            'solde_conge' => 28,
        ]);

        // Créer l'utilisateur Employé
        $userEmploye = User::create([
            'name' => 'Sophie Martin',
            'email' => 'employe@newbrain.com',
            'password' => Hash::make('password123'),
            'role_id' => 4, // Employe
        ]);

        Employe::create([
            'user_id' => $userEmploye->id,
            'matricule' => 'EMP-2024-003',
            'nom' => 'Martin',
            'prenom' => 'Sophie',
            'poste_actuel' => 'Développeuse Frontend',
            'service' => 'Informatique',
            'date_embauche' => '2022-03-20',
            'solde_conge' => 22,
        ]);
    }
}