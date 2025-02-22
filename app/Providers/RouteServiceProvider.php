<?php
// app/Providers/RouteServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\\Http\\Controllers';

    public function map()
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();  // Este método es el que carga las rutas de api.php
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api') // Las rutas de la API se definen con el prefijo 'api'
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));  // Asegúrate de que api.php esté siendo cargado aquí
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
}
