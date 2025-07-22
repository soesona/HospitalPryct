<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       // User::factory()->create([
       //     'name' => 'Test User',
       //     'email' => 'test@example.com',
       // ]);
       $roleAdmin = Role::create(['name' => 'admin']);
       $rolePaciente = Role::create(['name' => 'paciente']);
       $roleDoctor = Role::create(['name' => 'doctor']);

       Permission::create(['name' => 'Ver clientes']);
        Permission::create(['name' => 'Ver proveedores']);
         Permission::create(['name' => 'Ver empleados']);

         $AdminUser = User::query()->create(['name'=>'Admin', 'email' =>'hola@gmail.com', 'password' => '12345', 
         'email_verified_at' => now()]);

            $PacienteUser = User::query()->create(['name'=>'paciente', 'email' =>'hola1@gmail.com', 'password' => '12345', 
         'email_verified_at' => now()]);

           $DoctorUser = User::query()->create(['name'=>'doctor', 'email' =>'hola2@gmail.com', 'password' => '12345', 
         'email_verified_at' => now()]); 

         $AdminUser->assignRole($roleAdmin);
         $PacienteUser->assignRole($rolePaciente);
           $DoctorUser->assignRole($roleDoctor);

         $controlTotal = Permission::query()->pluck('name');
            $roleAdmin->syncPermissions($controlTotal);

            $rolePaciente->syncPermissions('Ver clientes');
            $roleDoctor->syncPermissions('Ver clientes', 'Ver proveedores', 'Ver empleados');
             
    }
}
