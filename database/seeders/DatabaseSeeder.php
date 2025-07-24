<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        $roleAdmin = Role::create(['name' => 'admin']);
        $rolePaciente = Role::create(['name' => 'paciente']);
        $roleDoctor = Role::create(['name' => 'doctor']);

     
        $permissions = [
          
            'ver historial clinico propio',
            'crear cita',
            
          
            'ver pacientes asignados',
            'ver historial pacientes atendidos',
            'manejar horario',
            'ver consultas',

            'gestionar usuarios',
            'gestionar roles y permisos',
            'gestionar citas',
            'gestionar pacientes',
            'gestionar doctores',
            'gestionar especialidades',
            'gestionar medicamentos',
            'gestionar historial clinico',
            'gestionar enfermedades',
          
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
//user ejemplos
    $adminUser = User::create([
    'nombreCompleto' => 'Admin',
    'email' => 'admin@hospital.com',
    'password' => Hash::make('tengo100'),
    'email_verified_at' => now(),
    'is_active' => true,
    'identidad' => '0801199012345',      
    'telefono' => '98765432',             
    'fechaNacimiento' => '1980-01-01',  
]);

$pacienteUser = User::create([
    'nombreCompleto' => 'Paciente',
    'email' => 'paciente@hospital.com',
    'password' => Hash::make('tengo100'),
    'email_verified_at' => now(),
    'is_active' => true,
    'identidad' => '0801199023456',
    'telefono' => '91234567',
    'fechaNacimiento' => '1995-05-15',
]);

$doctorUser = User::create([
    'nombreCompleto' => 'Doctor',
    'email' => 'doctor@hospital.com',
    'password' => Hash::make('tengo100'),
    'email_verified_at' => now(),
    'is_active' => true,
    'identidad' => '0801199034567',
    'telefono' => '99887766',
    'fechaNacimiento' => '1985-09-20',
]);

        
        $adminUser->assignRole($roleAdmin);
        $pacienteUser->assignRole($rolePaciente);
        $doctorUser->assignRole($roleDoctor);
       
        $doctorUser->assignRole($rolePaciente);

      

       
        $rolePaciente->givePermissionTo([
            'ver historial clinico propio',
            'crear cita',
            
        ]);

        
        $roleDoctor->givePermissionTo([
            'ver historial clinico propio',
            'crear cita',
            'ver pacientes asignados',
            'ver historial pacientes atendidos',
            'manejar horario',
            'ver consultas',
        ]);

        
        $roleAdmin->givePermissionTo([
            'gestionar usuarios',
            'gestionar roles y permisos',
            'gestionar citas',
            'gestionar pacientes',
            'gestionar doctores',
            'gestionar especialidades',
            'gestionar medicamentos',
            'gestionar historial clinico',
            'gestionar enfermedades',
        ]);
    }
}