<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    /*return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);*/
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return redirect('products');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/{product}/remove', [ProductController::class, 'remove'])->name('products.remove');
    Route::post('products/{product}/addToOrder', [ProductController::class, 'addToOrder'])->name('products.order.add');
    Route::post('products/{product}/removeFromOrder', [ProductController::class, 'removeFromOrder'])->name('products.order.remove');
    Route::put('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore')->withTrashed();

    //Warehouses
    Route::resource('warehouses', WarehouseController::class);
    Route::put('warehouses/{warehouse}/restore', [WarehouseController::class, 'restore'])->name('warehouses.restore')->withTrashed();

    //Users
    Route::resource('users', UserController::class);

    //Categories
    Route::resource('categories', CategoryController::class);

    //Orders
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/finish', [OrderController::class, 'finishOrder'])->name('orders.finish');
    Route::delete('orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
});

require __DIR__.'/auth.php';
