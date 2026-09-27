{{-- Компонент шапки (Header) --}}
<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-badge">NEWS</span>
            <span class="logo-text">Новости Колледжа</span>
        </a>

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
            <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                Администратор
            </a>
        </nav>
    </div>
</header>
