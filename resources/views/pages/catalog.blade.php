{{-- Страница 2: Каталог — лента новостей только одной темы (Искусственный интеллект) --}}
<x-layout title="Каталог">
    <div class="page-header">
        <h1 class="page-title">Каталог: {{ $category }}</h1>
        <p class="page-subtitle">В этом разделе отображаются только новости по выбранной теме «{{ $category }}»</p>
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
            <h3>В этой теме пока нет новостей</h3>
            <p>Добавьте новость с темой «{{ $category }}» через раздел <a href="{{ route('journalist') }}">«Журналист»</a>.</p>
        </div>
    @endif
</x-layout>
