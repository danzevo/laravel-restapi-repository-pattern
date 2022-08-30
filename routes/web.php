<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('admin/product', [App\Http\Controllers\ProductController::class, 'index'])->name('product');
    Route::get('admin/product/katalog', [App\Http\Controllers\ProductController::class, 'katalog'])->name('katalog');
    Route::get('admin/product/get-data', [App\Http\Controllers\ProductController::class, 'getData']);
    Route::post('admin/product/save', [App\Http\Controllers\ProductController::class, 'store']);
    Route::post('admin/product/destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
    Route::post('admin/upload-product', [App\Http\Controllers\ProductController::class, 'upload_product']);
    Route::get('admin/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category');
    Route::get('admin/category/get-data', [App\Http\Controllers\CategoryController::class, 'getData']);
    Route::post('admin/category/save', [App\Http\Controllers\CategoryController::class, 'store']);
    Route::put('admin/category/update/{id}', [App\Http\Controllers\CategoryController::class, 'update']);
    Route::post('admin/category/destroy/{id}', [App\Http\Controllers\CategoryController::class, 'destroy']);
});
