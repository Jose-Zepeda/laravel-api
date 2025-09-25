<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'subdomain',
        'config'
    ];

    protected $casts = [
        'config' => 'array'
    ];

    // Relaciones
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'tenant_id');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'tenant_id');
    }
}
