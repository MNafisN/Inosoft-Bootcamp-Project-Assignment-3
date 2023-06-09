<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

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

Route::group([
    'prefix' => 'auth'
], function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('data', [AuthController::class, 'data']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::group([
    'prefix' => 'todo',
    'middleware' => 'auth:api'
], function() {
    Route::post('add_todo', [TodoController::class, 'addTodo']);
    Route::get('get_todo', [TodoController::class, 'getTodoList']);
    Route::post('update_todo', [TodoController::class, 'updateTodo']);
    Route::post('delete_todo', [TodoController::class, 'deleteTodo']);
});

/**
 * Catatan untuk ditanyakan: Mengapa tidak bisa mengirimkan Request ke sebuah Route API 
 * yang menggunakan metode DELETE? (request kosong jika dites dump & destroy di controller)
 * Testing dilakukan dari postman metode delete yang sudah dilengkapi form data dari Body
*/