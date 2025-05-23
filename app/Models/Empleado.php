<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable = [
        'usuario_id',
        'dni',
        'nombre',
        'apellido',
        'telefono',
        'direccion',
        'foto', // nuevo campo agregado
        'fecha_ingreso'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
