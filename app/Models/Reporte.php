<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';

    protected $fillable = [
        'codigo',
        'nombre',
        'headers',
        'rows',
    ];

    // Para que Laravel maneje automáticamente las fechas
    public $timestamps = true;

    // Puedes usar casts para acceder fácilmente al JSON como array
    protected $casts = [
        'headers' => 'array',
        'rows' => 'array',
    ];
}
