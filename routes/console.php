<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule::command('inicio-stocks')
//     ->dailyAt('08:00')   // valor1
//     ->withoutOverlapping()
//     ->onOneServer();

// Schedule::command('fin-stocks')
//     ->dailyAt('20:00')   // valor2
//     ->withoutOverlapping()
//     ->onOneServer();

Schedule::command('inicio-stocks')
    ->everyMinute()
    ->withoutOverlapping();

Schedule::command('fin-stocks')
    ->everyMinute()
    ->withoutOverlapping();
