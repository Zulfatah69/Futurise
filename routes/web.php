<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');
Route::inertia('/berita', 'berita')->name('berita');
Route::inertia('/video', 'video')->name('video');
Route::inertia('/flyer', 'flyer')->name('flyer');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
