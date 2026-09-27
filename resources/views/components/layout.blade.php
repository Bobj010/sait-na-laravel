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
            max-width: 900px;
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
            padding: 16px 20px;
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
            gap: 8px;
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

        /* 2. Основная часть (Контент) */
        .main-content {
            flex: 1;
            padding: 35px 20px;
        }

        .page-header {
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
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

        /* Уведомление об успехе */
        .alert-success {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        /* Ошибки валидации */
        .alert-danger {
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

        /* 4. Стили формы Журналиста */
        .form-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
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
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        .btn-danger {
            background-color: #ef4444;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        /* 5. Таблица для Администратора */
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
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .admin-table td {
            padding: 14px 16px;
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
            margin-bottom: 25px;
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

        /* 6. Стили подвала (Footer) */
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
