@props(['news'])

{{-- Компонент карточки новости по рисунку преподавателя --}}
<article class="news-card">
    <div class="news-card-header">
        <span class="news-id">№ {{ $news->id }}</span>
        <span class="news-category">{{ $news->category }}</span>
        <span class="news-date">{{ $news->created_at ? $news->created_at->format('d.m.Y H:i') : 'Только что' }}</span>
    </div>
    
    <h3 class="news-title">{{ $news->title }}</h3>
    
    <div class="news-content">
        {{ $news->content }}
    </div>
</article>
