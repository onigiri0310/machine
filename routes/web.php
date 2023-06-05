<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/list', [App\Http\Controllers\ProductController::class, 'list'])->name('list');
Route::get('/search', [App\Http\Controllers\ProductController::class, 'search'])->name('search');
Route::get('/product_register', [App\Http\Controllers\ProductController::class, 'ProductRegister'])->name('ProductRegister');
Route::post('/Product', [App\Http\Controllers\ProductController::class,'store'])->name('store');
Route::get('/detail/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('detail');
Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('destroy');
Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class,'edit'])->name('edit');
Route::put('/update/{id}', [App\Http\Controllers\ProductController::class,'update'])->name('update');
