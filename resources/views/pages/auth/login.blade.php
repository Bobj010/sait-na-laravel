{{-- Страница входа в систему --}}
<x-layout title="Вход в систему">
    <div class="page-header" style="justify-content: center; text-align: center;">
        <div>
            <h1 class="page-title">Авторизация</h1>
            <p class="page-subtitle">Войдите в свой аккаунт для доступа к публикации и управлению</p>
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email адрес</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    value="{{ old('email') }}" 
                    placeholder="name@example.com" 
                    required 
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Пароль</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="••••••••" 
                    required
                >
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember" style="font-size: 0.9rem; color: #475569; cursor: pointer;">Запомнить меня</label>
            </div>

            <button type="submit" class="btn" style="width: 100%;">
                Войти
            </button>

            <div style="margin-top: 15px; text-align: center; font-size: 0.9rem; color: #64748b;">
                Ещё нет аккаунта? <a href="{{ route('register') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">Зарегистрироваться</a>
            </div>
        </form>

        {{-- Блок быстрого входа для преподавателя --}}
        <div class="demo-login-box">
            <p><strong>⚡ Быстрый вход для преподавателя (в 1 клик):</strong></p>
            <div class="demo-buttons">
                <a href="{{ route('quick-login', 'admin') }}" class="btn-demo btn-demo-admin" title="Вход с полным доступом ко всем разделам и админке">
                    👑 Администратор
                </a>
                <a href="{{ route('quick-login', 'journalist') }}" class="btn-demo btn-demo-journalist" title="Вход с доступом к созданию статей">
                    ✍️ Журналист
                </a>
                <a href="{{ route('quick-login', 'user') }}" class="btn-demo btn-demo-user" title="Вход как обычный читатель">
                    👤 Читатель
                </a>
            </div>
            <div style="font-size: 0.75rem; color: #64748b; margin-top: 8px;">
                Все тестовые пароли: <code>12345</code>
            </div>
        </div>
    </div>
</x-layout>
