{{-- Редактирование статьи администратором --}}
<x-layout title="Редактирование статьи">
    <div class="page-header">
        <div>
            <h1 class="page-title">Редактирование статьи #{{ $news->id }}</h1>
            <p class="page-subtitle">Внесите необходимые изменения в текст или заголовок публикации</p>
        </div>
        <div>
            <a href="{{ route('admin') }}" class="btn btn-secondary">← Назад в админку</a>
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.news.update', $news) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">Заголовок статьи</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    class="form-input" 
                    value="{{ old('title', $news->title) }}" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Тема / Категория</label>
                <select id="category" name="category" class="form-select" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category', $news->category) == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Текст статьи</label>
                <textarea 
                    id="content" 
                    name="content" 
                    class="form-textarea" 
                    style="min-height: 200px;" 
                    required
                >{{ old('content', $news->content) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn">
                    💾 Сохранить изменения
                </button>
                <a href="{{ route('admin') }}" class="btn btn-secondary" style="text-decoration: none;">
                    Отмена
                </a>
            </div>
        </form>
    </div>
</x-layout>
