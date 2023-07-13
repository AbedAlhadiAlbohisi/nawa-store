<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\EnsureUserType;
use App\Models\Order;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth','auth.type:admin,super-admin'])->prefix('/admin')->group(function(){

 Route::get('/products/trashed',[ProductController::class , 'trashed'])->name('products.trashed');
 Route::put('/products/{product}/restore',[ProductController::class , 'restore'])->name('products.restore');

 Route::delete('/products/{product}/force' , [ProductController::class , 'forceDelete'])->name('products.force-delete');

 Route::resource('/products', ProductController::class);
 Route::resource('/categories', CategoryController::class);
 Route::resource('/orders', OrderController::class);
 });
