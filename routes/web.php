<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuBrowseController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\OwnerOrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffAccountController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\StaffMenuController;
use App\Http\Controllers\StaffOrderController;
use App\Models\Menu;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu/{category?}', [MenuBrowseController::class, 'index'])->name('menu');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/update-note', [CartController::class, 'updateNote'])->name('cart.updateNote');
Route::delete('/cart/remove/{menuId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/payment-proof', [CheckoutController::class, 'uploadPaymentProof'])->name('checkout.paymentProof');

Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    Route::get('/orders', [StaffOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [StaffOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [StaffOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::middleware(['auth', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    Route::get('/menus', [StaffMenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/create', [StaffMenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [StaffMenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{menu}/edit', [StaffMenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{menu}', [StaffMenuController::class, 'update'])->name('menus.update');
    Route::patch('/menus/{menu}/toggle', [StaffMenuController::class, 'toggleActive'])->name('menus.toggle');

    Route::get('/orders', [OwnerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OwnerOrderController::class, 'show'])->name('orders.show');

    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    Route::get('/staff', [StaffAccountController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffAccountController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffAccountController::class, 'store'])->name('staff.store');
    Route::patch('/staff/{user}/toggle', [StaffAccountController::class, 'toggleActive'])->name('staff.toggle');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});
