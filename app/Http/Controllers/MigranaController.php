<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\TipoMigrana;

class MigranaController extends Controller
{
    public function registrarMigrana()
    {
        return view('usuarios.registrarMigrana');
    }

    public function seguimientoMigrana()
    {
        $migranas = TipoMigrana::query()
            ->where('usuario_id', Auth::id())
            ->orderBy('hora_inicio')
            ->get();

        $diasMigrana = $migranas
            ->groupBy(fn($migrana) => Carbon::parse($migrana->hora_inicio)->format('Y-m-d'))
            ->sortKeys();

        $conteoResumen = [];

        foreach ($migranas as $migrana) {
            $inicio = Carbon::parse($migrana->hora_inicio)->startOfDay();
            $fin = $migrana->hora_termina
                ? Carbon::parse($migrana->hora_termina)->startOfDay()
                : $inicio;

            foreach (CarbonPeriod::create($inicio, $fin) as $dia) {
                $clave = $dia->format('Y-m-d');
                $conteoResumen[$clave][$migrana->intensidad] = ($conteoResumen[$clave][$migrana->intensidad] ?? 0) + 1;
            }
        }

        ksort($conteoResumen);

        $resumenDias = collect($conteoResumen)->map(fn($niveles, $fecha) => [
            'fecha' => $fecha,
            'leve' => $niveles['leve'] ?? 0,
            'moderada' => $niveles['moderada'] ?? 0,
            'fuerte' => $niveles['fuerte'] ?? 0,
        ])->values();

        return view('usuarios.seguimientoMigrana', compact('diasMigrana', 'resumenDias'));
    }

    public function guardarMigrana(Request $request)
    {
        $datos = $request->validate([
            'tipo_migrana' => ['required', 'string'],
            'intensidad' => ['required', 'string'],
            'hora_inicio' => ['required', 'date'],
            'hora_termina' => ['required', 'date', 'after_or_equal:hora_inicio'],
            'notas' => ['nullable', 'string', 'max:250'],
        ], [
            'tipo_migrana.required' => 'Selecciona un tipo de migraña.',
            'intensidad.required' => 'Selecciona la intensidad.',
            'hora_inicio.required' => 'Indica la hora de inicio.',
            'hora_termina.required' => 'Indica la hora de fin.',
            'hora_termina.after_or_equal' => 'La hora de fin debe ser igual o posterior a la de inicio.',
        ]);

        TipoMigrana::create([
            'id' => (string) Str::uuid(),
            'usuario_id' => Auth::id(),
            ...$datos,
        ]);

        return redirect()
            ->route('usuarios.seguimientoMigrana')
            ->with('success', 'Migraña registrada correctamente.');
    }

    public function eliminarMigrana(string $id)
    {
        $migrana = TipoMigrana::query()
            ->where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        $migrana->delete();

        return redirect()
            ->route('usuarios.seguimientoMigrana')
            ->with('success', 'Registro de migraña eliminado correctamente.');
    }

    public function listarMigranas()
    {
        $tipoMigranas = TipoMigrana::all();
        return view('usuarios.listarMigranas', compact('tipoMigranas'));
    }
}