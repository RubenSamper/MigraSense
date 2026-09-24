<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
            ->groupBy(fn ($migrana) => Carbon::parse($migrana->hora_inicio)->format('Y-m-d'))
            ->sortKeys();

        $resumenDias = $diasMigrana->map(fn ($episodios, $fecha) => [
            'fecha' => $fecha,
            'cantidad' => $episodios->count(),
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
