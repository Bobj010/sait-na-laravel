{{-- Страница регистрации нового читателя --}}
<x-layout title="Регистрация">
    <div class="page-header" style="justify-content: center; text-align: center;">
        <div>
            <h1 class="page-title">Регистрация</h1>
            <p class="page-subtitle">Создайте аккаунт читателя для доступа к порталу</p>
        </div>
    </div>

    <div class="form-card">
        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Ваше имя / логин</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-input" 
                    value="{{ old('name') }}" 
                    placeholder="Например, Иван Петров" 
                    required 
                    autofocus
                >
            </div>

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
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Пароль (минимум 4 символа)</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="••••••••" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Повторите пароль</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-input" 
                    placeholder="••••••••" 
                    required
                >
            </div>

            <button type="submit" class="btn" style="width: 100%;">
                Зарегистрироваться
            </button>

            <div style="margin-top: 15px; text-align: center; font-size: 0.9rem; color: #64748b;">
                Уже есть аккаунт? <a href="{{ route('login') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600;">Войти</a>
            </div>
        </form>
    </div>
</x-layout>
