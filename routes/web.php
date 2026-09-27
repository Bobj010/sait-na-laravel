<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// 1. Главная страница со всеми новостями
Route::get('/', [NewsController::class, 'index'])->name('home');

// 2. Каталог новостей по фиксированной теме (Искусственный интеллект)
Route::get('/catalog', [NewsController::class, 'catalog'])->name('catalog');

// 3. Журналист (форма создания статьи) и обработка отправки
Route::get('/journalist', [NewsController::class, 'journalist'])->name('journalist');
Route::post('/journalist', [NewsController::class, 'store'])->name('journalist.store');

// 4. Администратор и удаление новости
Route::get('/admin', [NewsController::class, 'admin'])->name('admin');
Route::delete('/admin/news/{news}', [NewsController::class, 'destroy'])->name('admin.destroy');
