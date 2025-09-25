<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Tarea extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'tareas';
    
    protected $fillable = [
        'tenant_id',
        'titulo',
        'descripcion',
        'estado',
        'usuario_id',
        'fecha_vencimiento'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
