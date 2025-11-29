<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "INICIO";
});

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    return 'Cache eliminado <a href="/">Ir al inicio</a>';
})->name('clear.cache');


require __DIR__ . '/auth.php';
