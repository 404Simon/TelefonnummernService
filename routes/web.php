<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('', Dashboard::class)
    ->name('home');

require __DIR__.'/auth.php';
