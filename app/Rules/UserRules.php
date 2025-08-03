<?php

namespace App\Rules;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRules
{
    public static function store()
    {
        return [
            'nombreCompleto' => ['required', 'string', 'max:60', 'regex:/^[\pL\s]+$/u'],
            'email' => ['required', 'email', 'max:255', 'not_regex:/\s/', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'identidad' => ['required', 'digits:13', 'unique:users,identidad'],
             'fechaNacimiento' => ['required', 'date', 'before_or_equal:today'],
            'telefono' => ['required', 'digits:8'],
        ];
    }

    public static function messages()
    {
        return [
            'nombreCompleto.regex' => 'El nombre solo debe contener letras y espacios.',
            'email.not_regex' => 'El email no puede contener espacios.',
            'identidad.digits' => 'La identidad debe tener exactamente 13 números.',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 números.',
            'identidad.unique' => 'Esta identidad ya está en uso.',
            'email.unique' => 'Este email ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'fechaNacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser posterior a hoy.',
        ];
    }

    public static function update($codigoUsuario)
    {
        return [
            'password' => ['nullable', 'string', 'min:8'],
            'nombreCompleto' => ['required', 'string', 'max:60', 'regex:/^[\pL\s]+$/u'],
            'email' => [
                'required', 'email', 'max:255', 'not_regex:/\s/',
                Rule::unique('users', 'email')->ignore($codigoUsuario, 'codigoUsuario')
            ],
            'identidad' => [
                'required', 'digits:13',
                Rule::unique('users', 'identidad')->ignore($codigoUsuario, 'codigoUsuario')
            ],
            'fechaNacimiento' => ['required', 'date'],
            'telefono' => ['required', 'digits:8'],
        ];
    }
}