<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/orders', [ApiController::class, 'getOrders']);
Route::post('/orders', [ApiController::class, 'addOrder']);
Route::get('/orders/{id}', [ApiController::class, 'getOrder'])->where('id', '[0-9]+');
Route::post('/orders/{id}/payment', [ApiController::class, 'setOrderPayment'])->where('id', '[0-9]+');
Route::post('/orders/{id}/complete', [ApiController::class, 'setOrderCompleted'])->where('id', '[0-9]+');
Route::get('/orders/filters/created', [ApiController::class, 'getCreatedOrders']);
Route::get('/orders/filters/paid', [ApiController::class, 'getPaidOrders']);
Route::get('/products', [ApiController::class, 'getProducts']);
Route::get('/preparations/by-orders', [ApiController::class, 'getPreparationsByOrders']);
Route::get('/preparations/by-products', [ApiController::class, 'getPreparationsByProducts']);
