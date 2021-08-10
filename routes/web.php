<?php

use App\Http\Controllers\AutoResponderController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Servers\EmbedController;
use App\Models\AutoResponse;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [PageController::class, 'home'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/auth/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/servers', [PageController::class, 'servers'])->name('servers');
    Route::prefix('/servers/{server}')->group(function () {
        Route::get('/dashboard', [ServerController::class, 'dashboard'])->name('servers.dashboard');

        Route::prefix('/embed-messages')->group(function () {
            Route::get('/', [EmbedController::class, 'index'])->name('servers.embed-messages');
        });
        Route::prefix('/auto-responder')->group(function () {
            Route::bind('auto_response', function ($value) {
                return AutoResponse::find($value);
            });

            Route::get('/', [AutoResponderController::class, 'index'])->name('servers.auto-responders');
            Route::get('/{auto_response}', [AutoResponderController::class, 'destroy'])->name('servers.auto-responders.destroy');
            Route::post('/', [AutoResponderController::class, 'store']);
        });
    });
});

Route::middleware('guest')->prefix('/auth')->group(function () {
    Route::get('/redirect', [LoginController::class, 'redirect'])->name('login');
    Route::get('/callback', [LoginController::class, 'callback']);
});
