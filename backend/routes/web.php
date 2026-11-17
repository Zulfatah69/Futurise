<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'app' => 'FUTURiSE Backend API',
        'status' => 'online',
        'version' => app()->version(),
    ]);
});

