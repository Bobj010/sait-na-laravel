@props(['title' => 'Новостной сайт'])
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — Новости</title>
    <style>
        /* Базовые стили для простого и аккуратного сайта */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f9;
            color: #2c3e50;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }

        /* 1. Стили шапки и меню */
        .site-header {
            background-color: #1e293b;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .logo-badge {
            background-color: #3b82f6;
            color: #ffffff;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 1px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .nav-link {
            text-decoration: none;
            color: #cbd5e1;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: #ffffff;
            background-color: #334155;
        }

        .nav-link.active {
            color: #ffffff;
            background-color: #3b82f6;
        }

        /* Блок пользователя в шапке */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .user-name {
            color: #f1f5f9;
            font-weight: 600;
        }

        .badge-role {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-role-admin {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .badge-role-journalist {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .badge-role-user {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #7dd3fc;
        }

        .btn-logout {
            background: #334155;
            color: #cbd5e1;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #ffffff;
        }

        .auth-links {
            display: flex;
            gap: 8px;
        }

        .btn-auth-link {
            text-decoration: none;
            color: #ffffff;
            border: 1px solid #475569;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-auth-link:hover, .btn-auth-link.active {
            background-color: #334155;
            border-color: #3b82f6;
        }

        .btn-auth-register {
            text-decoration: none;
            background-color: #3b82f6;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-auth-register:hover {
            background-color: #2563eb;
        }

        /* 2. Основная часть (Контент) */
        .main-content {
            flex: 1;
            padding: 35px 20px;
        }

        .page-header {
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            font-size: 1.8rem;
            color: #0f172a;
            font-weight: 700;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 4px;
        }

        /* Уведомления */
        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-danger, .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .alert-danger ul {
            margin-left: 20px;
            margin-top: 5px;
        }

        /* Фильтрация категорий (по требованию преподавателя) */
        .filter-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .category-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .category-pill {
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            transition: all 0.2s ease;
        }

        .category-pill:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .category-pill.active {
            background: #3b82f6;
            color: #ffffff;
        }

        /* 3. Вертикальный список карточек новостей (по рисунку препода) */
        .news-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .news-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .news-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .news-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 0.85rem;
            flex-wrap: wrap;
        }

        .news-id {
            background-color: #f1f5f9;
            color: #475569;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .news-category {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .news-date {
            color: #94a3b8;
            margin-left: auto;
        }

        .news-title {
            font-size: 1.3rem;
            color: #0f172a;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .news-content {
            color: #475569;
            font-size: 1rem;
            white-space: pre-line;
        }

        .empty-state {
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            color: #64748b;
        }

        /* 4. Стили форм */
        .form-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s ease;
            font-family: inherit;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-edit {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .btn-edit:hover {
            background-color: #d97706;
        }

        .btn-danger {
            background-color: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            background-color: #64748b;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background-color: #475569;
        }

        /* 5. Блок быстрого входа для преподавателя */
        .demo-login-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 18px;
            margin-top: 25px;
            text-align: center;
        }

        .demo-login-box p {
            color: #1e40af;
            font-size: 0.95rem;
            margin-bottom: 12px;
        }

        .demo-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-demo {
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: transform 0.1s ease;
            display: inline-block;
        }

        .btn-demo:hover {
            transform: scale(1.02);
        }

        .btn-demo-admin {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .btn-demo-journalist {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .btn-demo-user {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #7dd3fc;
        }

        /* 6. Таблицы для Администратора */
        .admin-section {
            margin-bottom: 35px;
        }

        .admin-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .admin-section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f172a;
        }

        .admin-table-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
        }

        .admin-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .admin-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 18px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #3b82f6;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-select-sm {
            padding: 6px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 0.85rem;
            outline: none;
        }

        /* 7. Стили подвала (Footer) */
        .site-footer {
            background-color: #1e293b;
            color: #94a3b8;
            padding: 24px 20px;
            text-align: center;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .footer-copy {
            color: #f1f5f9;
            margin-bottom: 4px;
        }

        .footer-sub {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    {{-- Вызов компонента шапки --}}
    <x-header />

    {{-- Основной контент страницы --}}
    <main class="main-content container">
        {{-- Flash-сообщение об успехе --}}
        @if (session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Flash-сообщение об ошибке --}}
        @if (session('error'))
            <div class="alert-error">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        {{-- Ошибки валидации (если есть) --}}
        @if ($errors->any())
            <div class="alert-danger">
                <strong>Пожалуйста, исправьте следующие ошибки:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Содержимое конкретной страницы попадает в слот --}}
        {{ $slot }}
    </main>

    {{-- Вызов компонента подвала --}}
    <x-footer />

</body>
</html>
