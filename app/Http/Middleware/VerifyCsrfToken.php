<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;


class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'http://localhost:8000/clientes',             // Endpoint para Cliente
        'cliente-etiqueta',    // Endpoint para ClienteEtiqueta
        'label',               // Endpoint para Etiqueta
        'notification',        // Endpoint para Notificación
    ];
}
