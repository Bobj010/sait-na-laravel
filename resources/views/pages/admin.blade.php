{{-- Страница 4: Администратор — расширенная панель управления статьями и ролями --}}
<x-layout title="Администратор">
    <div class="page-header">
        <div>
            <h1 class="page-title">Панель администратора</h1>
            <p class="page-subtitle">Полный контроль контента: редактирование, добавление, удаление и управление ролями пользователей</p>
        </div>
        <div>
            <a href="{{ route('journalist') }}" class="btn">
                ➕ Добавить новость
            </a>
        </div>
    </div>

    {{-- 1. Статистика сайта --}}
    <div class="admin-stats">
        <div class="stat-box">
            <div class="stat-number">{{ $news->count() }}</div>
            <div class="stat-label">Всего публикаций</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $users->count() }}</div>
            <div class="stat-label">Пользователей в системе</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
            <div class="stat-label">Администраторов</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $users->where('role', 'journalist')->count() }}</div>
            <div class="stat-label">Журналистов</div>
        </div>
    </div>

    {{-- 2. Секция управления новостями (редактирование, удаление) --}}
    <div class="admin-section">
        <div class="admin-section-header">
            <h2 class="admin-section-title">📰 Управление публикациями ({{ $news->count() }})</h2>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Заголовок</th>
                        <th style="width: 170px;">Категория</th>
                        <th style="width: 140px;">Дата</th>
                        <th style="width: 180px; text-align: center;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $item)
                        <tr>
                            <td><strong>#{{ $item->id }}</strong></td>
                            <td>{{ $item->title }}</td>
                            <td>
                                <span class="news-category">{{ $item->category }}</span>
                            </td>
                            <td>{{ $item->created_at ? $item->created_at->format('d.m.Y H:i') : '—' }}</td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    {{-- Кнопка редактирования --}}
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-edit" style="text-decoration: none;">
                                        ✏️ Редактировать
                                    </a>

                                    {{-- Кнопка удаления --}}
                                    <form action="{{ route('admin.destroy', $item) }}" method="POST" onsubmit="return confirm('Вы действительно хотите удалить эту новость?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 30px;">
                                Новостей в базе данных пока нет.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. Секция управления пользователями и ролями (по требованию преподавателя) --}}
    <div class="admin-section">
        <div class="admin-section-header">
            <h2 class="admin-section-title">👥 Управление пользователями и ролями ({{ $users->count() }})</h2>
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Имя пользователя</th>
                        <th>Email</th>
                        <th style="width: 140px;">Текущая роль</th>
                        <th style="width: 250px;">Изменить роль</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr>
                            <td><strong>#{{ $u->id }}</strong></td>
                            <td>
                                <strong>{{ $u->name }}</strong>
                                @if ($u->id === auth()->id())
                                    <span style="font-size: 0.75rem; color: #3b82f6;">(Это вы)</span>
                                @endif
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge-role badge-role-{{ $u->role }}">
                                    {{ $u->role_title }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.role', $u) }}" method="POST" style="display: flex; gap: 6px; align-items: center;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="form-select-sm" {{ $u->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>Читатель (user)</option>
                                        <option value="journalist" {{ $u->role === 'journalist' ? 'selected' : '' }}>Журналист (journalist)</option>
                                        <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Администратор (admin)</option>
                                    </select>
                                    @if ($u->id !== auth()->id())
                                        <button type="submit" class="btn btn-sm">
                                            Сохранить
                                        </button>
                                    @else
                                        <span style="font-size: 0.8rem; color: #94a3b8;">Нельзя сменить</span>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
