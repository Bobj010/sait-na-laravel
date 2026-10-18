{{-- Страница 2: Каталог — тематическая лента новостей с переключением разделов --}}
<x-layout title="Каталог">
    <div class="page-header">
        <div>
            <h1 class="page-title">Каталог: {{ $selectedCategory }}</h1>
            <p class="page-subtitle">В этом разделе отображаются новости выбранной рубрики</p>
        </div>

        {{-- Выпадающий список тем --}}
        <div>
            <form method="GET" action="{{ route('catalog') }}" style="display: flex; gap: 8px; align-items: center;">
                <label for="catalog-select" style="font-size: 0.85rem; font-weight: 600; color: #475569;">Рубрика:</label>
                <select id="catalog-select" name="category" class="form-select-sm" onchange="this.form.submit()">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- Навигация по рубрикам каталога --}}
    <div class="filter-bar">
        <span class="filter-label">📂 Рубрики каталога:</span>
        <div class="category-pills">
            @foreach ($categories as $cat)
                <a href="{{ route('catalog', ['category' => $cat]) }}" class="category-pill {{ $selectedCategory === $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>

    @if ($news->count() > 0)
        {{-- Вертикальный список карточек только по выбранной теме --}}
        <div class="news-list">
            @foreach ($news as $item)
                <x-news-card :news="$item" />
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h3>В теме «{{ $selectedCategory }}» пока нет новостей</h3>
            <p>Вы можете опубликовать новость через раздел <a href="{{ route('journalist') }}">«Журналист»</a>.</p>
        </div>
    @endif
</x-layout>
