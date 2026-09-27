{{-- Страница 3: Журналист — форма создания новой статьи --}}
<x-layout title="Журналист">
    <div class="page-header">
        <h1 class="page-title">Панель журналиста</h1>
        <p class="page-subtitle">Заполните форму ниже, чтобы опубликовать новую статью на сайте</p>
    </div>

    <div class="form-card">
        <form action="{{ route('journalist.store') }}" method="POST">
            {{-- Защита от CSRF-атак, обязательная в Laravel --}}
            @csrf

            {{-- 1. Поле «Заголовок» --}}
            <div class="form-group">
                <label for="title" class="form-label">Заголовок статьи <span style="color: #ef4444;">*</span></label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-input" 
                    placeholder="Введите понятный и яркий заголовок..." 
                    value="{{ old('title') }}" 
                    required
                >
            </div>

            {{-- Категория новости (чтобы статья могла попасть в Каталог) --}}
            <div class="form-group">
                <label for="category" class="form-label">Тема / Категория</label>
                <select id="category" name="category" class="form-select">
                    <option value="Искусственный интеллект" {{ old('category') == 'Искусственный интеллект' ? 'selected' : '' }}>Искусственный интеллект</option>
                    <option value="Технологии" {{ old('category') == 'Технологии' ? 'selected' : '' }}>Технологии</option>
                    <option value="Космос" {{ old('category') == 'Космос' ? 'selected' : '' }}>Космос</option>
                    <option value="Наука" {{ old('category') == 'Наука' ? 'selected' : '' }}>Наука</option>
                </select>
            </div>

            {{-- 2. Поле «Текст статьи» --}}
            <div class="form-group">
                <label for="content" class="form-label">Текст статьи <span style="color: #ef4444;">*</span></label>
                <textarea 
                    id="content" 
                    name="content" 
                    class="form-textarea" 
                    placeholder="Напишите текст новости или статьи..." 
                    required
                >{{ old('content') }}</textarea>
            </div>

            {{-- 3. Кнопка отправки/создания --}}
            <button type="submit" class="btn">
                Опубликовать статью
            </button>
        </form>
    </div>
</x-layout>
