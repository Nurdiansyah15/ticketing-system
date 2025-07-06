<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\TicketConversationController;
use App\Http\Controllers\ReportController;




// Route untuk halaman utama (home)
// Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk home (hanya bisa diakses setelah login)
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::resource('tickets', TicketController::class)->except(['destroy']);
    Route::get('/password/edit', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/tickets/{ticket}/conversations', [TicketConversationController::class, 'store'])->name('tickets.conversations.store');
    Route::patch('/tickets/{ticket}/mark-resolved', [TicketConversationController::class, 'markResolved'])->name('tickets.markResolved');
    Route::post('/tickets/{ticket}/not-resolved', [TicketConversationController::class, 'notResolved'])->name('tickets.notResolved');
    Route::get('/tickets/{ticket}/rating', [TicketConversationController::class, 'showRating'])->name('tickets.rating.show');
    Route::post('/tickets/{ticket}/rating', [TicketConversationController::class, 'submitRating'])->name('tickets.rating.submit');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// Route untuk admin (hanya bisa diakses oleh admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Route untuk kelola tiket
    Route::get('/tickets', [TicketController::class, 'adminIndex'])->name('admin.tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'adminShow'])->name('admin.tickets.show');
    Route::put('/tickets/{ticket}', [TicketController::class, 'adminUpdate'])->name('admin.tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'adminDestroy'])->name('admin.tickets.destroy');

    // Route untuk kelola pengguna
    Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('admin.reports.print');


    Route::patch('/tickets/{ticket}/start', [TicketConversationController::class, 'start'])->name('tickets.start');
    Route::patch('/tickets/{ticket}/propose-resolved', [TicketConversationController::class, 'askResolved'])->name('tickets.proposeResolved');
});
