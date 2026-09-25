<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialRiesgo extends Model
{
    protected $table = 'historial_riesgo';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'usuario_id',
        'fecha',
        'nivel',
        'variacion_hpa',
    ];
}