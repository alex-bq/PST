<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/obtener-valores-lote',
        '/procesar-formulario',
        'modificar-planilla/*',
        '/eliminar-corte',
        '/eliminar-calidad',
        '/eliminar-destino',
        '/eliminar-calibre',
    ];
}
