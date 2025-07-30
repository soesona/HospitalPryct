<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $primaryKey = 'codigoUsuario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombreCompleto',
        'email',
        'password',
        'identidad',
        'fechaNacimiento',
        'telefono',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function initials(): string
    {
        return Str::of($this->nombreCompleto)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'codigoUsuario', 'codigoUsuario');
    }

    public function paciente()
{
    return $this->hasOne(Paciente::class, 'codigoUsuario', 'codigoUsuario');
}

}