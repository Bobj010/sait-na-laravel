{{-- Страница 3: Журналист — форма создания новой статьи --}}
<x-layout title="Журналист">
    <div class="page-header">
        <div>
            <h1 class="page-title">Панель журналиста</h1>
            <p class="page-subtitle">Публикация новостей доступна только для пользователей с ролью «Журналист» или «Администратор»</p>
        </div>
        <div>
            <span class="badge-role badge-role-{{ auth()->user()->role }}">
                Вы вошли как: {{ auth()->user()->role_title }}
            </span>
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('journalist.store') }}" method="POST">
            {{-- Защита от CSRF-атак --}}
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

            {{-- Категория новости --}}
            <div class="form-group">
                <label for="category" class="form-label">Тема / Категория <span style="color: #ef4444;">*</span></label>
                <select id="category" name="category" class="form-select" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
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
