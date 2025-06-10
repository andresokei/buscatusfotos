<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sesion/{slug}', [SessionController::class, 'show'])->name('session.show');
Route::get('/carrito', [CartController::class, 'view'])->name('cart.view');
Route::post('/carrito', [CartController::class, 'update'])->name('cart.update');

// Rutas de administración
// Rutas de login
Route::get('/admin/login', [App\Http\Controllers\Admin\LoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Admin\LoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [App\Http\Controllers\Admin\LoginController::class, 'logout'])->name('admin.logout');

// Rutas de administración protegidas
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.index');
    Route::get('/sesiones', [App\Http\Controllers\Admin\SessionAdminController::class, 'index'])->name('admin.sessions');
    Route::get('/sesiones/crear', [App\Http\Controllers\Admin\SessionAdminController::class, 'create'])->name('admin.session.create');
    Route::post('/sesiones', [App\Http\Controllers\Admin\SessionAdminController::class, 'store'])->name('admin.session.store');
    Route::get('/sesiones/{id}/fotos', [App\Http\Controllers\Admin\SessionAdminController::class, 'photos'])->name('admin.session.photos');
    Route::post('/sesiones/{id}/fotos', [App\Http\Controllers\Admin\SessionAdminController::class, 'uploadPhoto'])->name('admin.session.upload');
    Route::delete('/sesiones/{id}', [App\Http\Controllers\Admin\SessionAdminController::class, 'destroy'])->name('admin.session.destroy');
    
    // ⭐ NUEVA RUTA - Agregar esta línea:
    Route::delete('/fotos/{photo}', [App\Http\Controllers\Admin\SessionAdminController::class, 'deletePhoto'])->name('admin.photo.delete');
});

Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'createSession'])->name('checkout.create'); 

Route::get('/descargar/{token}', [App\Http\Controllers\DownloadController::class, 'show'])->name('download.show');

Route::get('/descargar/{token}/download', [App\Http\Controllers\DownloadController::class, 'download'])->name('download.file');

Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

Route::post('/webhooks/stripe', [App\Http\Controllers\WebhookController::class, 'handleStripe'])->name('webhook.stripe');