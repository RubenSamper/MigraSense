<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMigrana extends Model
{
    protected $table = "migranas";

    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'usuario_id',
        'tipo_migrana',
        'hora_inicio',
        'hora_termina',
        'intensidad',
        'sintomas',
        'datos_extra',
        'notas',
    ];
}
