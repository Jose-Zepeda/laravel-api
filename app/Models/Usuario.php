<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Para login con Auth
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\BelongsToTenant;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, BelongsToTenant;

    protected $table = 'usuarios'; // Nombre exacto de la tabla

    protected $fillable = [
        'tenant_id',
        'nombre',
        'email',
        'password',
        'rol',
    ];

    // Ocultar el password al devolver el modelo en JSON
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }
}
