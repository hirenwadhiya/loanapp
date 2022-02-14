<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', [AuthController::class, 'login'])->name('user.login');
Route::post('/get-loan', [LoanController::class, 'getLoan'])->name('loan.get');
