<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::get('/', function () {
    return response()->redirectTo('login');
})->name('login');

Route::get('/bank', [BankController::Class, 'index'])->name('index');
Route::get('/bank/create', [BankController::Class, 'create']);
Route::post('/bank/create', [BankController::Class, 'create']);
Route::get('/bank/detail', [BankController::class, 'detail']);

Route::get('api/bank/account', [BankController::class, 'getAccount']);
Route::post('api/bank/add/balance', [BankController::class, 'addBalance']);
Route::get('api/bank/detail', [BankController::class, 'getDetail']);

Route::get('google/auth', [RegisterController::class, 'googleAuth'])->name('googleLogin');
Route::get('google/auth/callback', [RegisterController::class, 'googleAuthCallback']);

Auth::routes();
Route::get('/home', [BankController::Class, 'index']);
