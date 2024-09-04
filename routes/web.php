<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/inicio');

Volt::route('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Volt::route('/inicio', 'inicio.index')
    ->middleware('auth')
    ->name('inicio.index');

Volt::route('/configuracion/oficinas', 'configuracion.oficina.index')
    ->middleware('auth')
    ->name('configuracion.oficina.index');

Volt::route('/configuracion/complejidad-incidencia', 'configuracion.complejidad-incidencia.index')
    ->middleware('auth')
    ->name('configuracion.complejidad-incidencia.index');

//