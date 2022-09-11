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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::middleware('noAdmin')->group(function () {

    Route::any('/', function () {
        return redirect()->route('posts.index');
    });
    Route::resource('posts', PostController::class)->only('index');
    Route::middleware('auth')->group(function () {
        Route::resource('posts', PostController::class)->except('index');
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('posts', [UserController::class, 'posts'])->name('posts');
        });

        Route::get('/interface', function () {
            return Inertia::render('Interface');
        })->middleware(['auth', 'verified'])->name('interface');
    });
});


require __DIR__ . '/auth.php';
