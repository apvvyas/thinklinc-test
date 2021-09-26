<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    LoginController, RegisterController, SearchController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('user')->group(function () {
    Route::post('login', [LoginController::class, 'attempt'])->name('user.login');
    Route::post('register', [RegisterController::class, 'attempt'])->name('user.register');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('youtube')->group(function () {
        Route::name('youtube.')->group(function () {
            Route::get('search', [SearchController::class, 'search'])->name('search');
            Route::get('show/{youtube}', [SearchController::class, 'show'])->name('show');
        });
    });
});
