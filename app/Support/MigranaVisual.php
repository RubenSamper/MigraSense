<?php

namespace App\Support;

class MigranaVisual
{
    public const ICONOS = [
        'con_aura' => '⚡',
        'sin_aura' => '🤕',
        'menstrual' => '🩸',
        'vestibular' => '😵‍💫',
        'cronica' => '📅',
    ];

    public const ORDEN_INTENSIDAD = ['leve', 'moderada', 'fuerte'];

    public static function icono(string $tipo): string
    {
        return self::ICONOS[$tipo] ?? '❓';
    }

    public static function etiquetaTipo(string $tipo): string
    {
        return ucfirst(str_replace('_', ' ', $tipo));
    }

    public static function estrellas(string $nivel): string
    {
        $posicion = array_search($nivel, self::ORDEN_INTENSIDAD, true);
        $llenas = $posicion === false ? 0 : $posicion + 1;
        $total = count(self::ORDEN_INTENSIDAD);

        return str_repeat('★', $llenas) . str_repeat('☆', $total - $llenas);
    }
}