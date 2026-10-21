<?php

namespace App\Support;

use App\Models\TipoMigranaCatalogo;

class MigranaVisual
{
    public const ORDEN_INTENSIDAD = ['leve', 'moderada', 'fuerte'];

    // Caché en memoria del catálogo de tipos de migraña, para no repetir la
    // misma consulta una y otra vez mientras se atiende una sola petición
    // (aquí se usa varias veces por cada episodio que se muestra en pantalla).
    private static ?\Illuminate\Support\Collection $catalogo = null;

    private static function catalogo(): \Illuminate\Support\Collection
    {
        if (self::$catalogo === null) {
            self::$catalogo = TipoMigranaCatalogo::orderBy('orden')->get()->keyBy('clave');
        }

        return self::$catalogo;
    }

    // Se llama después de crear, editar o borrar un tipo de migraña en el
    // catálogo dentro de la misma petición, para que una relectura posterior
    // no se quede con la lista vieja en caché.
    public static function olvidarCatalogo(): void
    {
        self::$catalogo = null;
    }

    public static function tiposMigrana(): \Illuminate\Support\Collection
    {
        return self::catalogo()->values();
    }

    public static function icono(string $tipo): string
    {
        return self::catalogo()->get($tipo)?->icono ?? '❓';
    }

    public static function etiquetaTipo(string $tipo): string
    {
        return self::catalogo()->get($tipo)?->etiqueta ?? ucfirst(str_replace('_', ' ', $tipo));
    }

    public static function estrellas(string $nivel): string
    {
        $posicion = array_search($nivel, self::ORDEN_INTENSIDAD, true);
        $llenas = $posicion === false ? 0 : $posicion + 1;
        $total = count(self::ORDEN_INTENSIDAD);

        return str_repeat('★', $llenas) . str_repeat('☆', $total - $llenas);
    }
}