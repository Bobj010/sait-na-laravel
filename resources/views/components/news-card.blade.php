@props(['news'])

{{-- Компонент карточки новости по рисунку преподавателя со связями 1:N --}}
<article class="news-card">
    <div class="news-card-header">
        <span class="news-id">№ {{ $news->id }}</span>
        <span class="news-category">{{ $news->category?->name ?? 'Без рубрики' }}</span>
        <span class="news-date">{{ $news->created_at ? $news->created_at->format('d.m.Y H:i') : 'Только что' }}</span>
    </div>
    
    <h3 class="news-title">{{ $news->title }}</h3>
    
    <div class="news-content">
        {{ $news->content }}
    </div>

    <div style="margin-top: 12px; padding-top: 8px; border-top: 1px dashed #e2e8f0; font-size: 0.82rem; color: #64748b; display: flex; align-items: center; gap: 6px;">
        <span>✍️ <strong>Автор:</strong> {{ $news->user?->name ?? 'Редакция' }}</span>
    </div>
</article>
