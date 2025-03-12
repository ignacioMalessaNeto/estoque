<?php

use App\Http\Controllers\api\AddressController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CategoriesController;
use App\Http\Controllers\api\ItensController;
use App\Http\Controllers\api\MovimentsController;
use App\Http\Controllers\api\StockController;
use App\Http\Controllers\api\TokensController;
use App\Http\Controllers\api\OutsController;
use App\Http\Middleware\CheckIsLogged;
use App\Models\Moviment;
use Illuminate\Support\Facades\Route;
// Auth route
Route::post('/api/loginSubmit', [AuthController::class, 'login']);
// Decode token
// Route::get('api/token', [TokensController::class, "decryptToken"]);

// Middleware for verify if user is auth
Route::middleware([CheckIsLogged::class])->group(function () {
  // Routes Auth 
  Route::post('/api/logout', [AuthController::class, 'logout']);
  Route::get('/api/renoveToken', [TokensController::class, 'renoveToken']);
  Route::post('/api/signUpSubmit', [AuthController::class, 'signUp']);

  // Routes Itens
  Route::get('/api/itens', [ItensController::class, 'index']);
  Route::get('/api/itens/{id}', [ItensController::class, 'show']);
  Route::delete('/api/itens/{id}', [ItensController::class, 'destroy']);
  Route::post('/api/itens', [ItensController::class, 'create']);
  Route::post('/api/itens/{id}', [ItensController::class, 'update']);

  // Routes Address
  Route::get('/api/address', [AddressController::class, 'index']);
  Route::get('/api/address/{id}', [AddressController::class, 'show']);
  Route::delete('/api/address/{id}', [AddressController::class, 'destroy']);
  Route::post('/api/address', [AddressController::class, 'create']);
  Route::post('/api/address/{id}', [AddressController::class, 'update']);


  // Routes Categories
  Route::get('/api/category', [CategoriesController::class, 'index']);
  Route::get('/api/category/{id}', [CategoriesController::class, 'show']);
  Route::delete('/api/category/{id}', [CategoriesController::class, 'destroy']);
  Route::post('/api/category', [CategoriesController::class, 'create']);
  Route::post('/api/category/{id}', [CategoriesController::class, 'update']);


  // Routes Stock
  Route::get('/api/stock', [StockController::class, 'index']);
  Route::get('/api/stock/{id}', [StockController::class, 'show']);
  Route::delete('/api/stock/{id}', [StockController::class, 'destroy']);
  Route::post('/api/stock', [StockController::class, 'create']);
  Route::post('/api/stock/{id}', [StockController::class, 'update']);

  // Routes Outs
  Route::get('/api/out', [OutsController::class, 'index']);
  Route::get('/api/out/{id}', [OutsController::class, 'show']);
  Route::delete('/api/out/{id}', [OutsController::class, 'destroy']);
  Route::post('/api/out', [OutsController::class, 'create']);
  Route::post('/api/out/{id}', [OutsController::class, 'update']);

  // Routes Moviments
  Route::get('/api/moviments', [MovimentsController::class, 'index']);
  Route::get('/api/moviments/{id}', [MovimentsController::class, 'show']);
  // Route::delete('/api/moviments/{id}', [MovimentsController::class, 'destroy']);
  Route::post('/api/moviments', [MovimentsController::class, 'create']);
  Route::post('/api/moviments/{id}', [MovimentsController::class, 'update']);
});
