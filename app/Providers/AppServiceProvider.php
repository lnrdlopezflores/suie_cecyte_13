<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    // app/Providers/AppServiceProvider.php
public function boot(): void
{
    try {
        if (Schema::hasTable('configuraciones_sistema')) {
            $configs = DB::table('configuraciones_sistema')->pluck('valor', 'clave')->toArray();
            
            View::share('colorPrimario',    $configs['color_primario'] ?? '#841B44');
            View::share('colorHover',       $configs['color_hover'] ?? '#681535');
            View::share('colorLight',       $configs['color_light'] ?? '#fdf2f4');
            View::share('mostrarAvisoVeda', ($configs['mostrar_aviso_veda'] ?? '0') === '1');
        } else {
            View::share('colorPrimario',    '#841B44');
            View::share('colorHover',       '#681535');
            View::share('colorLight',       '#fdf2f4');
            View::share('mostrarAvisoVeda', false);
        }
    } catch (\Throwable $e) {
        View::share('colorPrimario',    '#841B44');
        View::share('colorHover',       '#681535');
        View::share('colorLight',       '#fdf2f4');
        View::share('mostrarAvisoVeda', false);
    }
}
}