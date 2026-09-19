<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class SelectIntensidad extends Component
{
    public $listado;
    public $seleccionado;

    public function __construct($seleccionado = null)
    {
        $this->seleccionado = $seleccionado;

        $columna = DB::selectOne(
            "SHOW COLUMNS FROM `migranas` WHERE Field = 'intensidad'"
        );
        $tipo = $columna->Type ?? '';

        $this->listado = str_starts_with($tipo, 'enum(')
            ? str_getcsv(substr($tipo, 5, -1), ',', "'")
            : [];
    }

    public function render()
    {
        return view('components.select-intensidad');
    }
}
