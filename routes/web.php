<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;
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

Route::get('/list', [App\Http\Controllers\ListController::class, 'list'])->name('list');
Route::get('/search', [App\Http\Controllers\ListController::class, 'search'])->name('list.search');
Route::get('/product_register', [App\Http\Controllers\ProductController::class, 'ProductRegister'])->name('ProductRegister');
Route::post('/Product', [App\Http\Controllers\ProductController::class,'store'])->name('Product.store');
Route::get('/detail/{id}', [App\Http\Controllers\ListController::class, 'show'])->name('detail');
Route::delete('/products/{id}', [App\Http\Controllers\ListController::class, 'destroy'])->name('list.destroy');
Route::get('/edit/{id}', [App\Http\Controllers\ProductController::class,'edit'])->name('edit');
Route::put('/update/{id}', [App\Http\Controllers\ProductController::class,'update'])->name('update');
