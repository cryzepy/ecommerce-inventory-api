<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// fitur tambahan untuk produk
// mencari produk berdasarkan nama produk atau kategori
Route::get('/products/search', [ProductController::class, 'searchProductsByNameOrCategory']);
// Mengupdate jumlah stok produk setelah transaksi
Route::post('/products/update-stock', [ProductController::class, 'updateProductStock']);
// mengambil total nilai inventory berdasarkan harga dan jumlah stok produk
Route::get('/inventory/value', [ProductController::class, 'getTotalInventoryValue']);

// API Routes untuk produk
Route::apiResource('products', ProductController::class);

// API Routes untuk kategori
Route::apiResource('categories', CategoryController::class);

