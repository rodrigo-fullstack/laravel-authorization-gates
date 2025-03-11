<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/{data?}', [ProductController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/products/update/{data?}', [ProductController::class, 'change'])->middleware(['auth', 'verified'])->name('product.update');
Route::post('/products/update', [ProductController::class, 'update'])->middleware(['auth', 'verified'])->name('product.update.submit');

Route::get('/products/create', [ProductController::class, 'create'])->middleware(['auth', 'verified'])->name('product.create');

Route::post('/products/create', [ProductController::class, 'store'])->middleware(['auth', 'verified'])->name('product.create.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
