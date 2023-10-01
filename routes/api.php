<?php

use Illuminate\Support\Facades\Route;

Route::post('categories/get',[\App\Http\Controllers\api\CategoryController::class, 'getCategories']);
Route::post('category/select',[\App\Http\Controllers\api\CategoryController::class, 'selectCategory']);
Route::post('category/save',[\App\Http\Controllers\api\CategoryController::class, 'saveCategory']);
Route::post('category/delete',[\App\Http\Controllers\api\CategoryController::class, 'delCategory']);

Route::post('products/get',[\App\Http\Controllers\api\ProductController::class,'getProduct']);
Route::post('product/save',[\App\Http\Controllers\api\ProductController::class,'saveProduct']);
Route::post('product/del', [\App\Http\Controllers\api\ProductController::class,'delProduct']);
Route::post('products/del', [\App\Http\Controllers\api\ProductController::class,'delProducts']);

Route::post('user/permissions', [\App\Http\Controllers\api\AccessController::class, 'getPermissions']);
Route::post('access/save', [\App\Http\Controllers\api\AccessController::class, 'savePermissions']);
