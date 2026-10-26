<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN
        $admin = User::updateOrCreate(
            ['email' => 'admin@newbrain.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('Admin123!'),
                'role_id' => 1,
            ]
        );
        Employe::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'matricule' => 'EMP-ADMIN-001',
                'nom' => 'Admin',
                'prenom' => 'Système',
                'poste_actuel' => 'Administrateur',
                'service' => 'Direction',
                'date_embauche' => '2024-01-01',
                'solde_conge' => 30,
            ]
        );

        // 2. RH
        $rh = User::updateOrCreate(
            ['email' => 'rh@newbrain.com'],
            [
                'name' => 'Responsable RH',
                'password' => Hash::make('Rh2024@123'),
                'role_id' => 2,
            ]
        );
        Employe::updateOrCreate(
            ['user_id' => $rh->id],
            [
                'matricule' => 'EMP-RH-001',
                'nom' => 'Laurent',
                'prenom' => 'Marie',
                'poste_actuel' => 'Responsable RH',
                'service' => 'Ressources Humaines',
                'date_embauche' => '2024-01-01',
                'solde_conge' => 30,
            ]
        );

        // 3. CHEF
        $chef = User::updateOrCreate(
            ['email' => 'chef@newbrain.com'],
            [
                'name' => 'Chef de service',
                'password' => Hash::make('Chef2024@123'),
                'role_id' => 3,
            ]
        );
        Employe::updateOrCreate(
            ['user_id' => $chef->id],
            [
                'matricule' => 'EMP-CHEF-001',
                'nom' => 'Dubois',
                'prenom' => 'Thomas',
                'poste_actuel' => 'Chef de service IT',
                'service' => 'Informatique',
                'date_embauche' => '2024-01-01',
                'solde_conge' => 30,
            ]
        );

        // 4. EMPLOYÉ
        $employe = User::updateOrCreate(
            ['email' => 'employe@newbrain.com'],
            [
                'name' => 'Employé',
                'password' => Hash::make('Emp2024@123'),
                'role_id' => 4,
            ]
        );
        Employe::updateOrCreate(
            ['user_id' => $employe->id],
            [
                'matricule' => 'EMP-001',
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'poste_actuel' => 'Développeuse',
                'service' => 'Informatique',
                'date_embauche' => '2024-01-01',
                'solde_conge' => 30,
            ]
        );
    }
}