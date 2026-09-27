{{-- Страница 1: Главная — лента всех новостей --}}
<x-layout title="Главная">
    <div class="page-header">
        <h1 class="page-title">Лента всех новостей</h1>
        <p class="page-subtitle">Последние события, обновления и актуальные публикации</p>
    </div>

    @if ($news->count() > 0)
        {{-- Вертикальный список карточек по рисунку на доске --}}
        <div class="news-list">
            @foreach ($news as $item)
                <x-news-card :news="$item" />
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h3>Новостей пока нет</h3>
            <p>Вы можете перейти в раздел <a href="{{ route('journalist') }}">«Журналист»</a> и опубликовать первую статью!</p>
        </div>
    @endif
</x-layout>
