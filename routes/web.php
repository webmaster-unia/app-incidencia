<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/login', 'auth.login')
    ->name('login');

Volt::route('/', 'home.index')
    ->name('home.index');

//
