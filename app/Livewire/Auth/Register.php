<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Rules\UserRules;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $nombreCompleto = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $identidad = '';
    public string $fechaNacimiento = '';
    public string $telefono = '';
    /**
     * Handle an incoming registration request.
     */
    public function register(): void
{
    $validated = $this->validate(UserRules::store(), UserRules::messages());
  
    $validated['nombreCompleto'] = mb_strtoupper($validated['nombreCompleto'], 'UTF-8');
    $validated['email'] = strtolower($validated['email']);
    $validated['identidad'] = strtoupper($validated['identidad']);
    $validated['telefono'] = strtoupper($validated['telefono']);
    $validated['password'] = Hash::make($validated['password']);


  
    $user = User::create($validated);
    $user->assignRole('Paciente');

    // Crear registro en pacientes vinculando el usuario
    \App\Models\Paciente::create([
        'codigoUsuario' => $user->codigoUsuario,
    ]);

   
    event(new Registered($user));
    Auth::login($user);


    $this->redirect(route('dashboard', absolute: false), navigate: true);
}

}