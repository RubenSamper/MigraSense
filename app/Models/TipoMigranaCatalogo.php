<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Catálogo de tipos de migraña (tabla `tipos_migrana`). Antes era una lista
// fija dentro del código (un ENUM en la base de datos + un array en
// App\Support\MigranaVisual); ahora vive aquí para poder añadir, renombrar
// o reordenar tipos sin tocar ni una línea de PHP.
class TipoMigranaCatalogo extends Model
{
    protected $table = 'tipos_migrana';

    protected $primaryKey = 'clave';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'clave',
        'etiqueta',
        'icono',
        'orden',
    ];
}