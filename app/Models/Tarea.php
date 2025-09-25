<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';
    
    protected $fillable = [
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
