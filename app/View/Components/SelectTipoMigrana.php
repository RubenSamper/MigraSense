<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

class SelectTipoMigrana extends Component
{
    public $listado;
    public $seleccionado;

    public function __construct($seleccionado = null)
    {
        $this->seleccionado = $seleccionado;

        $columna = DB::selectOne(
            "SHOW COLUMNS FROM `migranas` WHERE Field = 'tipo_migrana'"
        );
        $tipo = $columna->Type ?? '';

        $this->listado = str_starts_with($tipo, 'enum(')
            ? str_getcsv(substr($tipo, 5, -1), ',', "'")
            : [];
    }

    /**
     * Render the component.
     */

    public function render()
    {
        return view('components.select-tipo-migrana');
    }

}
