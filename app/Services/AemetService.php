<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AemetService
{
    private string $apiKey;
    private string $baseUrl = 'https://opendata.aemet.es/opendata/api';

    public function __construct()
    {
        $this->apiKey = config('services.aemet.api_key');
    }

    /**
     * AEMET responde en dos pasos: primero pide el recurso, que devuelve
     * una URL temporal en el campo "datos"; luego hay que descargar esa URL
     * para obtener el JSON real.
     */
    private function peticionDosPasos(string $endpoint): ?array
    {
        $primeraRespuesta = Http::withHeaders([
            'api_key' => $this->apiKey,
        ])->get($this->baseUrl . $endpoint);

        if (!$primeraRespuesta->successful()) {
            return null;
        }

        $cuerpo = $primeraRespuesta->json();

        if (!isset($cuerpo['datos'])) {
            return null;
        }

        $segundaRespuesta = Http::get($cuerpo['datos']);

        if (!$segundaRespuesta->successful()) {
            return null;
        }

        $textoUtf8 = mb_convert_encoding($segundaRespuesta->body(), 'UTF-8', 'ISO-8859-1');

        return json_decode($textoUtf8, true);
    }

    /**
     * Devuelve el listado completo de estaciones meteorológicas de AEMET.
     * Se cachea 30 días porque este listado casi nunca cambia.
     */
    public function obtenerEstaciones(): array
    {
        return Cache::remember('aemet_estaciones', now()->addDays(30), function () {
            return $this->peticionDosPasos('/valores/climatologicos/inventarioestaciones/todasestaciones') ?? [];
        });
    }

    /**
     * Devuelve las últimas observaciones (últimas ~24h, cada hora) de una
     * estación concreta, identificada por su código idema.
     */
    public function obtenerObservacionEstacion(string $idema): array
    {
        return $this->peticionDosPasos("/observacion/convencional/datos/estacion/{$idema}") ?? [];
    }

    /**
     * Convierte coordenadas AEMET (formato DDMMSSH, ej: "394924N") a grados decimales.
     */
    private function coordenadaADecimal(string $coordenada): float
    {
        $direccion = substr($coordenada, -1);
        $numeros = substr($coordenada, 0, -1);

        $grados = (int) substr($numeros, 0, 2);
        $minutos = (int) substr($numeros, 2, 2);
        $segundos = (int) substr($numeros, 4, 2);

        $decimal = $grados + ($minutos / 60) + ($segundos / 3600);

        if (in_array($direccion, ['S', 'W'])) {
            $decimal *= -1;
        }

        return $decimal;
    }

    /**
     * Encuentra la estación de AEMET más cercana a unas coordenadas dadas,
     * usando la fórmula de Haversine (distancia sobre la superficie terrestre).
     */
    public function estacionMasCercana(float $latUsuario, float $lonUsuario): ?array
    {
        $estaciones = $this->obtenerEstaciones();

        if (empty($estaciones)) {
            return null;
        }

        $masCercana = null;
        $distanciaMinima = null;

        foreach ($estaciones as $estacion) {
            if (empty($estacion['latitud']) || empty($estacion['longitud'])) {
                continue;
            }

            $latEstacion = $this->coordenadaADecimal($estacion['latitud']);
            $lonEstacion = $this->coordenadaADecimal($estacion['longitud']);

            $distancia = $this->distanciaHaversine($latUsuario, $lonUsuario, $latEstacion, $lonEstacion);

            if ($distanciaMinima === null || $distancia < $distanciaMinima) {
                $distanciaMinima = $distancia;
                $masCercana = $estacion;
                $masCercana['distancia_km'] = round($distancia, 1);
            }
        }

        return $masCercana;
    }

    private function distanciaHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $radioTierraKm = 6371;

        $deltaLat = deg2rad($lat2 - $lat1);
        $deltaLon = deg2rad($lon2 - $lon1);

        $a = sin($deltaLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radioTierraKm * $c;
    }

    private const UMBRALES_SENSIBILIDAD = [
        'poco_sensible' => ['amarillo' => 5, 'rojo' => 10],
        'sensible' => ['amarillo' => 3, 'rojo' => 6],
        'muy_sensible' => ['amarillo' => 1.5, 'rojo' => 3],
    ];

    /**
     * Calcula el nivel de riesgo de migraña (verde/amarillo/rojo) según la
     * variación de presión de las últimas observaciones y la sensibilidad
     * configurada por el usuario.
     */
    public function calcularRiesgoMigrana(array $observaciones, string $sensibilidad): array
    {
        $presiones = array_filter(array_map(
            fn($obs) => $obs['pres_nmar'] ?? null,
            $observaciones
        ), fn($valor) => $valor !== null);

        if (count($presiones) < 2) {
            return ['nivel' => 'desconocido', 'variacion_hpa' => null];
        }

        $variacion = max($presiones) - min($presiones);
        $umbrales = self::UMBRALES_SENSIBILIDAD[$sensibilidad] ?? self::UMBRALES_SENSIBILIDAD['sensible'];

        if ($variacion >= $umbrales['rojo']) {
            $nivel = 'rojo';
        } elseif ($variacion >= $umbrales['amarillo']) {
            $nivel = 'amarillo';
        } else {
            $nivel = 'verde';
        }

        return [
            'nivel' => $nivel,
            'variacion_hpa' => round($variacion, 1),
        ];
    }

    /**
     * Devuelve la observación actual de TODAS las estaciones que reportan
     * datos convencionales en tiempo real (no todas las estaciones del
     * inventario general los dan).
     */
    public function obtenerTodasLasObservacionesActuales(): array
    {
        return Cache::remember('aemet_observaciones_actuales', now()->addMinutes(15), function () {
            return $this->peticionDosPasos('/observacion/convencional/todas') ?? [];
        });
    }

    /**
     * Encuentra, entre las estaciones que SÍ reportan datos en tiempo real,
     * la más cercana a unas coordenadas dadas.
     */
    public function estacionConDatosMasCercana(float $latUsuario, float $lonUsuario): ?array
    {
        $observaciones = $this->obtenerTodasLasObservacionesActuales();

        if (empty($observaciones)) {
            return null;
        }

        $masCercana = null;
        $distanciaMinima = null;

        foreach ($observaciones as $obs) {
            // Algunas estaciones automáticas de AEMET no miden presión
            // atmosférica (solo temperatura, humedad, viento...). Como la
            // presión es el dato clave de toda la app (semáforo de riesgo,
            // gráfica, "Presión actual"), descartamos aquí cualquier
            // estación que no la reporte, aunque sea la más cercana en
            // distancia pura.
            if (!isset($obs['lat'], $obs['lon'], $obs['pres_nmar'])) {
                continue;
            }

            $distancia = $this->distanciaHaversine($latUsuario, $lonUsuario, (float) $obs['lat'], (float) $obs['lon']);

            if ($distanciaMinima === null || $distancia < $distanciaMinima) {
                $distanciaMinima = $distancia;
                $masCercana = $obs;
                $masCercana['distancia_km'] = round($distancia, 1);
            }
        }

        return $masCercana;
    }

    /**
     * Devuelve el listado completo de municipios de AEMET (necesario para
     * pedir la predicción horaria, que se consulta por municipio, no por
     * estación). Se cachea 30 días porque este listado casi nunca cambia.
     */
    public function obtenerMunicipios(): array
    {
        return Cache::remember('aemet_municipios', now()->addDays(30), function () {
            return $this->peticionDosPasos('/maestro/municipios') ?? [];
        });
    }

    /**
     * Encuentra el municipio de AEMET más cercano a unas coordenadas dadas.
     * Admite tanto campos ya decimales (latitud_dec/longitud_dec) como el
     * formato DDMMSSH de AEMET (latitud/longitud), según lo que venga.
     */
    public function municipioMasCercano(float $latUsuario, float $lonUsuario): ?array
    {
        $municipios = $this->obtenerMunicipios();

        if (empty($municipios)) {
            return null;
        }

        $masCercano = null;
        $distanciaMinima = null;

        foreach ($municipios as $municipio) {
            $lat = null;
            $lon = null;

            if (!empty($municipio['latitud_dec']) && !empty($municipio['longitud_dec'])) {
                $lat = (float) $municipio['latitud_dec'];
                $lon = (float) $municipio['longitud_dec'];
            } elseif (!empty($municipio['latitud']) && !empty($municipio['longitud'])) {
                try {
                    $lat = $this->coordenadaADecimal($municipio['latitud']);
                    $lon = $this->coordenadaADecimal($municipio['longitud']);
                } catch (\Throwable $e) {
                    continue;
                }
            }

            if ($lat === null || $lon === null) {
                continue;
            }

            $distancia = $this->distanciaHaversine($latUsuario, $lonUsuario, $lat, $lon);

            if ($distanciaMinima === null || $distancia < $distanciaMinima) {
                $distanciaMinima = $distancia;
                $masCercano = $municipio;
                $masCercano['distancia_km'] = round($distancia, 1);
            }
        }

        return $masCercano;
    }

    /**
     * Devuelve la predicción horaria de temperatura (°C) de AEMET para un
     * municipio, como lista de ['hora' => 'HH:00', 'valor' => float].
     * AEMET no da predicción de presión atmosférica en este producto, solo
     * temperatura (y otras variables que no usamos aquí).
     *
     * Se implementa a propósito de forma defensiva: si el formato del JSON
     * no es el esperado, se devuelve un array vacío en vez de lanzar un
     * error, para que el dashboard nunca se rompa por esto.
     */
    public function obtenerPrevisionHoraria(string $codigoMunicipio): array
    {
        // El maestro de municipios devuelve ids con el prefijo "id" (ej.
        // "id28079"); el endpoint de predicción espera solo el número.
        $codigo = preg_replace('/^id/', '', $codigoMunicipio);

        try {
            $respuesta = $this->peticionDosPasos("/prediccion/especifica/municipio/horaria/{$codigo}");

            if (!is_array($respuesta) || empty($respuesta[0]['prediccion']['dia'])) {
                return [];
            }

            $resultado = [];

            foreach ($respuesta[0]['prediccion']['dia'] as $dia) {
                $fecha = $dia['fecha'] ?? null;
                $temperaturas = $dia['temperatura'] ?? [];

                if (!$fecha || !is_array($temperaturas)) {
                    continue;
                }

                foreach ($temperaturas as $punto) {
                    if (!isset($punto['periodo'], $punto['value']) || $punto['value'] === '') {
                        continue;
                    }

                    $resultado[] = [
                        'fecha' => substr($fecha, 0, 10),
                        'hora' => sprintf('%02d:00', (int) $punto['periodo']),
                        'valor' => (float) $punto['value'],
                    ];
                }
            }

            return $resultado;
        } catch (\Throwable $e) {
            return [];
        }
    }
}