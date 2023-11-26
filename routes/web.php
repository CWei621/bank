<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return response()->redirectTo('login');
    });

    Route::get('/bank', [BankController::class, 'index'])->name('index');
    Route::get('/bank/balance', [BankController::class, 'create']);
    Route::post('/bank/balance', [BankController::class, 'addBalance']);
    Route::get('/bank/detail', [BankController::class, 'detail']);
});

Route::get('google/auth', [RegisterController::class, 'googleAuth'])->name('googleLogin');
Route::get('google/auth/callback', [RegisterController::class, 'googleAuthCallback']);

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes...
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
