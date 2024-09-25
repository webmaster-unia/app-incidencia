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

Volt::route('/seguridad/usuarios', 'seguridad.usuario.index')
    ->middleware('auth')
    ->name('seguridad.usuario.index');
    
Volt::route('/seguridad/roles', 'seguridad.rol.index')
    ->middleware('auth')
    ->name('seguridad.rol.index');
    
Volt::route('/configuracion/oficinas', 'configuracion.oficina.index')
    ->middleware('auth')
    ->name('configuracion.oficina.index');

Volt::route('/configuracion/complejidad-incidencia', 'configuracion.complejidad-incidencia.index')
    ->middleware('auth')
    ->name('configuracion.complejidad-incidencia.index');

Volt::route('/seguridad/permisos', 'seguridad.permiso.index')
    ->middleware('auth')
    ->name('seguridad.permiso.index');

Volt::route('/incidencia', 'gestion-incidencia.index')
    ->middleware('auth')
    ->name('gestion-incidencia.index');
    
//