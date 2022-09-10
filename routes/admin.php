<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('panel', [PostController::class, 'index'])->name('panel');
