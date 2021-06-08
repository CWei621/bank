<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;

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
    return view('welcome');
});

Route::get('/bank', [BankController::Class, 'index']);
Route::get('/bank/create', [BankController::Class, 'create']);
Route::get('/bank/detail', [BankController::class, 'detail']);

Route::get('api/bank/account', [BankController::class, 'getAccount']);
Route::post('api/bank/add/balance', [BankController::class, 'addBalance']);
Route::get('api/bank/detail', [BankController::class, 'getDetail']);

Auth::routes();
Route::get('/home', [BankController::Class, 'index']);
