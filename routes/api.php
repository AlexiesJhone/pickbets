<?php

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

// Route::post('login', 'PassportController@login');
//
// Route::post('register', 'PassportController@register');


// Route::get('/', function () {
//   return redirect('login');
// });

// Route::post('/login', [App\Http\Controllers\api\LoginController::class, 'login'])->name('login');
// Route::group(['middleware'=>'auth::api'], function(){
//   Route::get('/all', [App\Http\Controllers\api\LoginController::class, 'getall']);
// });


Route::middleware('auth:api')->get('/all', [App\Http\Controllers\api\apicontroller::class, 'getall']);
Route::middleware('auth:api')->get('/createapi', [App\Http\Controllers\api\apicontroller::class, 'alex']);
Route::middleware('auth:api')->post('/transferfunds', [App\Http\Controllers\api\apicontroller::class, 'transferfunds']);
