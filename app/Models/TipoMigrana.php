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
        'notas',
        'diagnostico',
        'leido_por_medico',
        'diagnostico_visto',
    ];

    protected $casts = [
        'leido_por_medico' => 'boolean',
        'diagnostico_visto' => 'boolean',
    ];

    // El tipo de migraña de este episodio, ya como registro del catálogo
    // (tabla tipos_migrana) en vez de un simple texto suelto.
    public function tipoMigranaCatalogo()
    {
        return $this->belongsTo(TipoMigranaCatalogo::class, 'tipo_migrana', 'clave');
    }
}