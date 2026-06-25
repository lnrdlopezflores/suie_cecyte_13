<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Docente;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // Asegúrate de que apunte a tu tabla si no se llama 'users'

    protected $fillable = [
        'username',
        'password',
        'activo',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // En Laravel 11 esto maneja el hash automático al CREAR usuarios.
    // Si usas Hash::make() al crear y además tienes esto, podrías estar doble-hasheando.
    protected function casts(): array
    {
        return [
            'password' => 'hashed', 
        ];
    }

    public function docente()
    {
        // 'usuario_id' es la clave foránea en la tabla 'docentes'
        // 'id' es la clave primaria en la tabla 'usuarios'
        return $this->hasOne(Docente::class, 'usuario_id', 'id');
    }
}