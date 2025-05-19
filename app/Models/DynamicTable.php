<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicTable extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si es diferente al modelo plural
    protected $table = 'dynamic_tables';

    // Definir los atributos que pueden ser asignados masivamente
    protected $fillable = [
        'table_name',
        'headers',
    ];

    // Si usas JSON para los headers, puedes agregar el atributo "casts" para facilitar el manejo
    protected $casts = [
        'headers' => 'array',
    ];
}
