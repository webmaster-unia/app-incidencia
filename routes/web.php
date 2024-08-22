<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/inicio');

Volt::route('/login', 'auth.login')
    ->name('login');

Volt::route('/inicio', 'inicio.index')
    ->name('inicio.index');

//
