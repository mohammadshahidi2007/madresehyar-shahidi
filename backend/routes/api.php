<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'madresehyar-shahidi-api',
        'version' => '0.1.0',
    ]);
})->name('api.health');
