<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';

    protected $fillable = [
        'rol_id',
        'modulo',
        'puede_ver',
        'puede_crear',
        'puede_editar',
        'puede_eliminar'
    ];

    // Relación con rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    
}
