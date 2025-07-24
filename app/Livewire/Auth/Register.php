<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
    $validated = $this->validate([
        'nombreCompleto' => ['required', 'string', 'max:60'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        'identidad' => ['required', 'string', 'size:13', 'unique:' . User::class],
        'fechaNacimiento' => ['required', 'date'],
        'telefono' => ['required', 'string', 'size:8'],
    ]);

    // Hasheamos la contraseña
    $validated['password'] = Hash::make($validated['password']);

    // Creamos el usuario con todos los campos validados
    $user = User::create($validated);

    // Asignamos el rol (si estás usando Spatie)
    $user->assignRole('Paciente');

    // Disparamos evento y autenticamos
    event(new Registered($user));
    Auth::login($user);

    // Redirigimos
    $this->redirect(route('dashboard', absolute: false), navigate: true);
}

}