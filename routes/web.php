<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::redirect('/', '/home');

Volt::route('/login', 'auth.login')
    ->name('login');

Volt::route('/home', 'home.index')
    ->name('home.index');

//
