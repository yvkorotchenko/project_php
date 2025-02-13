<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaravueController;

Route::get('gip',function(Illuminate\Http\Request $request) {
    return '<h1>' . $request->ip() . '</h1>';
});

Route::middleware(['web'])->group(function () {
   Route::get(env('MT_SPORTS_PAHT'), [LaravueController::class, 'index'])
       ->where('any', '.*')
       ->name('laravue');
});