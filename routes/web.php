<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', function () {
    return redirect()->route(auth('admin')->check() ? 'admin.panel' :
        'posts.index');
});
Route::resource('posts', PostController::class)->only('index')->middleware('guest:admin');
Route::middleware('auth:web')->group(function () {
    Route::resource('posts', PostController::class)->except('index', 'show');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('posts', [UserController::class, 'posts'])->name('posts');
    });

    Route::get('/interface', function () {
        return Inertia::render('Interface');
    })->name('interface');
});


require __DIR__ . '/auth.php';
