<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. Публичные маршруты (доступны всем пользователям и гостям)
// ==========================================
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/catalog', [NewsController::class, 'catalog'])->name('catalog');

// ==========================================
// 2. Аутентификация (Вход, Регистрация, Выход)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Быстрый вход для преподавателя в 1 клик
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');

// ==========================================
// 3. Раздел «Журналист» (доступен ролям: journalist и admin через Middleware)
// ==========================================
Route::middleware(['auth', 'role:journalist,admin'])->group(function () {
    Route::get('/journalist', [NewsController::class, 'journalist'])->name('journalist');
    Route::post('/journalist', [NewsController::class, 'store'])->name('journalist.store');
});

// ==========================================
// 4. Панель «Администратор» (доступна ТОЛЬКО роли: admin через Middleware)
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [NewsController::class, 'admin'])->name('admin');
    Route::get('/admin/news/{news}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/admin/news/{news}', [NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/admin/news/{news}', [NewsController::class, 'destroy'])->name('admin.destroy');

    // Управление ролями зарегистрированных пользователей
    Route::patch('/admin/users/{user}/role', [NewsController::class, 'updateUserRole'])->name('admin.users.role');
});
