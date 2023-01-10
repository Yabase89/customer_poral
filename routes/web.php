<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

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

Route::get('/',[RegisterController::class,'index']);
Route::get('/customer-signup',[RegisterController::class,'customersignup']);
Route::post('/customer-add',[RegisterController::class,'customeradd']);
Route::post('/admin-add',[RegisterController::class,'adminadd']);
Route::get('/admin-signup',[RegisterController::class,'adminsignup']);
Route::post('/sign-in',[RegisterController::class,'signin']);
Route::get('/dashboard',[RegisterController::class,'dashboard']);
Route::get('customer-approve/{id}',[RegisterController::class,'approve']);
Route::get('customer-reject/{id}',[RegisterController::class,'reject']);
Route::get('/logout',[RegisterController::class,'logout']);
