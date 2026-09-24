<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    protected $table = 'recordatorios';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'usuario_id',
        'titulo',
        'descripcion',
        'fecha_recordatorio',
        'completado',
    ];

    protected $casts = [
        'fecha_recordatorio' => 'datetime',
        'completado' => 'boolean',
    ];
}