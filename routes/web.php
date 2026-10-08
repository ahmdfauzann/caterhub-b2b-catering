<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MerchantPortalController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes - CaterHub (B2B Catering Marketplace)
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/catering/{slug}', [HomeController::class, 'merchantDetail'])->name('merchant.detail');
Route::get('/menu/{id}', [HomeController::class, 'menuDetail'])->name('menu.detail');

// Public Invoice Routes (supports slashes and dashes in invoice numbers)
Route::get('/invoice/{invoiceNumber}', [InvoiceController::class, 'show'])->name('invoice.show')->where('invoiceNumber', '.*');
Route::get('/invoice/{invoiceNumber}/print', [InvoiceController::class, 'print'])->name('invoice.print')->where('invoiceNumber', '.*');

// Cart Routes (Accessible by all users and guests)
Route::get('/cart', [CustomerPortalController::class, 'cart'])->name('customer.cart');
Route::post('/cart/add', [CustomerPortalController::class, 'addToCart'])->name('customer.cart.add');
Route::post('/cart/update', [CustomerPortalController::class, 'updateCart'])->name('customer.cart.update');
Route::delete('/cart/remove/{menuId}', [CustomerPortalController::class, 'removeFromCart'])->name('customer.cart.remove');
Route::post('/cart/clear', [CustomerPortalController::class, 'clearCart'])->name('customer.cart.clear');

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    
    Route::get('/register/customer', [AuthController::class, 'showRegisterCustomerForm'])->name('register.customer');
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);

    Route::get('/register/merchant', [AuthController::class, 'showRegisterMerchantForm'])->name('register.merchant');
    Route::post('/register/merchant', [AuthController::class, 'registerMerchant']);
});

// Authenticated Shared Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Customer (Office) Routes
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CustomerPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerPortalController::class, 'updateProfile'])->name('profile.update');

    // Checkout Routes
    Route::get('/checkout', [CustomerPortalController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CustomerPortalController::class, 'processCheckout'])->name('checkout.process');

    // Orders, Payment Confirmation & Reviews
    Route::get('/orders', [CustomerPortalController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [CustomerPortalController::class, 'orderDetail'])->name('orders.show');
    Route::post('/orders/{id}/payment', [CustomerPortalController::class, 'uploadPaymentReceipt'])->name('orders.payment');
    Route::post('/orders/{id}/review', [CustomerPortalController::class, 'storeReview'])->name('orders.review');
});

// Merchant (Catering Vendor) Routes
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/dashboard', [MerchantPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MerchantPortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [MerchantPortalController::class, 'updateProfile'])->name('profile.update');

    // Menu Management (CRUD)
    Route::get('/menus', [MerchantPortalController::class, 'menus'])->name('menus.index');
    Route::get('/menus/create', [MerchantPortalController::class, 'createMenu'])->name('menus.create');
    Route::post('/menus', [MerchantPortalController::class, 'storeMenu'])->name('menus.store');
    Route::get('/menus/{id}/edit', [MerchantPortalController::class, 'editMenu'])->name('menus.edit');
    Route::put('/menus/{id}', [MerchantPortalController::class, 'updateMenu'])->name('menus.update');
    Route::delete('/menus/{id}', [MerchantPortalController::class, 'deleteMenu'])->name('menus.destroy');

    // Order & Invoice Management
    Route::get('/orders', [MerchantPortalController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{id}', [MerchantPortalController::class, 'orderDetail'])->name('orders.show');
    Route::patch('/orders/{id}/status', [MerchantPortalController::class, 'updateOrderStatus'])->name('orders.update-status');
});
