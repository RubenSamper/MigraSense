<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Recordatorio;

class AlertasController extends Controller
{
    private const TITULOS_ALERTA_CLIMA = [
        'Posible migraña por condiciones meteorológicas',
        'Posible migraña por cambio de presión atmosférica',
        'Seguimiento de presión atmosférica',
    ];

    public function guardarAlertasClima(Request $request)
    {
        $datos = $request->validate([
            'alertas' => ['array', 'max:7'],
            'alertas.*.fecha' => ['required', 'date_format:Y-m-d'],
            'alertas.*.variacion_hpa' => ['required', 'numeric', 'gte:0'],
            'alertas.*.aviso' => ['required', 'string', 'max:250'],
            'alertas.*.riesgo_alto' => ['required'],
        ]);

        $usuarioId = Auth::id();

        $fechas = collect($datos['alertas'] ?? [])
            ->pluck('fecha');

        Recordatorio::query()
            ->where('usuario_id', $usuarioId)
            ->whereIn('titulo', self::TITULOS_ALERTA_CLIMA)
            ->when(
                $fechas->isNotEmpty(),
                fn ($query) => $query->whereNotIn(
                    'fecha_recordatorio',
                    $fechas->map(fn ($fecha) => $fecha . ' 00:00:00')
                )
            )
            ->delete();

        foreach ($datos['alertas'] ?? [] as $alerta) {

            $riesgoAlto = filter_var(
                $alerta['riesgo_alto'],
                FILTER_VALIDATE_BOOLEAN
            );

            $titulo = $riesgoAlto
                ? 'Posible migraña por cambio de presión atmosférica'
                : 'Seguimiento de presión atmosférica';

            Recordatorio::updateOrCreate(
                [
                    'usuario_id' => $usuarioId,
                    'titulo' => $titulo,
                    'fecha_recordatorio' => $alerta['fecha'] . ' 00:00:00',
                ],
                [
                    'id' => (string) Str::uuid(),
                    'descripcion' => $alerta['aviso'],
                    'completado' => false,
                ]
            );
        }

        return response()->json([
            'ok' => true
        ]);
    }
}