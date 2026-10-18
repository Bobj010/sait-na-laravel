{{-- Компонент шапки (Header) --}}
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-badge">NEWS</span>
            <span class="logo-text">Новости Колледжа</span>
        </a>

        {{-- 4 основных раздела по ТЗ --}}
        <nav class="nav-menu">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                Главная
            </a>
            <a href="{{ route('catalog') }}" class="nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}">
                Каталог
            </a>
            <a href="{{ route('journalist') }}" class="nav-link {{ request()->routeIs('journalist') ? 'active' : '' }}">
                Журналист
            </a>
            <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin*') ? 'active' : '' }}">
                Администратор
            </a>
        </nav>

        {{-- Блок авторизации пользователя / гостя --}}
        <div class="user-menu">
            @auth
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="badge-role badge-role-{{ auth()->user()->role }}">
                        {{ auth()->user()->role_title }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Выйти из аккаунта">
                            Выйти
                        </button>
                    </form>
                </div>
            @else
                <div class="auth-links">
                    <a href="{{ route('login') }}" class="btn-auth-link {{ request()->routeIs('login') ? 'active' : '' }}">Вход</a>
                    <a href="{{ route('register') }}" class="btn-auth-register {{ request()->routeIs('register') ? 'active' : '' }}">Регистрация</a>
                </div>
            @endauth
        </div>
    </div>
</header>
