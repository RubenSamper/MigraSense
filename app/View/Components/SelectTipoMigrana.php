<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\TipoMigranaCatalogo;

class SelectTipoMigrana extends Component
{
    public $listado;
    public $seleccionado;
    public $iconos;

    public function __construct($seleccionado = null)
    {
        $this->seleccionado = $seleccionado;

        // Antes la lista salía de leer el ENUM de la columna `tipo_migrana`
        // directamente de la base de datos (SHOW COLUMNS); ahora que esa
        // columna es texto normal enlazado a su propia tabla, la lista (y
        // los iconos, que antes venían de MigranaVisual::ICONOS) salen del
        // catálogo. Añadir o quitar un tipo es solo una fila en esa tabla.
        $tipos = TipoMigranaCatalogo::orderBy('orden')->get();

        $this->listado = $tipos->pluck('clave')->all();
        $this->iconos = $tipos->pluck('icono', 'clave')->all();
    }

    public function render()
    {
        return view('components.select-tipo-migrana');
    }
}