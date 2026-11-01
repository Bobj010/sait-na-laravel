# Архитектурная карта кода проекта (Code Map)
*Файл: `docs/CODE_MAP.md` | Версия: 1.1 | Статус: Актуально*

---

## 1. Общая структура файлов репозитория

```
sait_na_Laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php              # Базовый абстрактный контроллер
│   │   │   ├── AuthController.php          # Вход, регистрация, выход, quickLogin, auto-heal
│   │   │   └── NewsController.php          # Главная, каталог, создание, модерация, смена ролей
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          # Проверка прав доступа по ролям (admin, journalist)
│   ├── Models/
│   │   ├── News.php                        # Eloquent-модель новости ($fillable: title, category, content)
│   │   └── User.php                        # Модель пользователя (хелперы: isAdmin, isJournalist, role_title)
│   └── Providers/
│       └── AppServiceProvider.php          # Глобальные сервис-провайдеры Laravel
├── bootstrap/
│   └── app.php                             # Конфигурация приложения и регистрация alias 'role' для Middleware
├── config/
│   ├── app.php                             # Настройки приложения, локаль, APP_KEY fallback
│   ├── auth.php                            # Провайдеры пользователей и гарды аутентификации
│   └── database.php                        # Подключение SQLite по умолчанию
├── database/
│   ├── database.sqlite                     # Готовая предзаполненная база данных SQLite
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php  # Таблица пользователей с полем role
│   │   └── 2026_09_27_182241_create_news_table.php   # Таблица публикаций новостей
│   └── seeders/
│       ├── DatabaseSeeder.php              # Создание пользователей (admin, journalist, user) через firstOrCreate
│       └── NewsSeeder.php                  # Начальные публикации по 4 тематическим рубрикам
├── docs/                                   # Центр проектной документации по методологии Vibecoding
│   ├── PRODUCT_SPEC.md                     # Продуктовая спецификация для человека
│   ├── SPEC.md                             # Техническая спецификация для агента
│   ├── CODE_MAP.md                         # Настоящая архитектурная карта модулей
│   ├── DECISIONS.md                        # Журнал архитектурных решений (ADR)
│   ├── WORKFLOW_AND_VERIFICATION.md        # Регламент 3-слойной проверки качества
│   ├── SETUP.md                            # Пошаговый гайд по быстрому запуску
│   └── research/                           # Материалы исследований и расшифровки
├── resources/
│   └── views/
│       ├── components/                     # Переиспользуемые Blade-компоненты
│       │   ├── layout.blade.php            # Каркас HTML5 + встроенные стили (без сборщиков)
│       │   ├── header.blade.php            # Шапка: 4 раздела + статус «👤 Читатель» / «Вход для редакции»
│       │   ├── footer.blade.php            # Единый подвал сайта
│       │   └── news-card.blade.php         # Карточка новости по макету с доски (№ ID, бейдж, дата)
│       └── pages/                          # Страницы разделов
│           ├── home.blade.php              # Главная: общая лента всех новостей + панель фильтрации
│           ├── catalog.blade.php           # Каталог: выбор рубрик и тематическая лента
│           ├── journalist.blade.php        # Форма добавления статьи
│           ├── news-edit.blade.php         # Форма редактирования статьи для администратора
│           ├── admin.blade.php             # Админка: модерация контента + смена ролей пользователей
│           └── auth/
│               ├── login.blade.php         # Форма входа + 1-click демо-кнопки для проверки ролей
│               └── register.blade.php      # Форма регистрации читателя
├── routes/
│   └── web.php                             # Все веб-маршруты приложения с middleware-группами
├── tests/
│   └── Feature/
│       └── NewsPagesTest.php               # Набор из 8 автотестов (45 проверок, 100% green)
├── .env.example                            # Эталонный файл переменных окружения
├── README.md                               # Главный файл репозитория для преподавателя
└── start.bat                               # Скрипт запуска в 1 клик для Windows в колледже
```

---

## 2. Сквозные пути взаимодействия компонентов

### Цепочка: Создание новости журналистом

```
[resources/views/pages/journalist.blade.php]  (HTML-форма, метод POST, токен @csrf)
                          │
                          ▼  POST /journalist
[routes/web.php]                              (Маршрут с middleware: 'auth', 'role:journalist,admin')
                          │
                          ▼  Проверка прав
[app/Http/Middleware/RoleMiddleware.php]       (Проверяет auth()->check() и in_array(role))
                          │
                          ▼  Вызов метода
[app/Http/Controllers/NewsController@store]    (Валидация $request->validate)
                          │
                          ▼  News::create($validated)
[app/Models/News.php]                         (Eloquent ORM)
                          │
                          ▼  INSERT INTO news
[database/database.sqlite]                    (Фиксация записи на диске)
                          │
                          ▼  Редирект с flash-сообщением
[resources/views/pages/home.blade.php]        (Показ новой карточки через <x-news-card />)
```

---

## 3. Таблица ответственности ключевых классов

| Класс / Файл | Роль в системе | Ключевые методы |
|---|---|---|
| `app/Models/User.php` | Модель пользователя | `isAdmin()`, `isJournalist()`, `getRoleTitleAttribute()` |
| `app/Models/News.php` | Модель новости | Eloquent CRUD операции, `$fillable` |
| `app/Http/Controllers/NewsController.php` | Контроллер контента и модерации | `index()`, `catalog()`, `store()`, `update()`, `destroy()`, `updateUserRole()` |
| `app/Http/Controllers/AuthController.php` | Контроллер аутентификации | `login()`, `register()`, `logout()`, `quickLogin()`, `ensureUserExists()` |
| `app/Http/Middleware/RoleMiddleware.php` | Охранник маршрутов | `handle($request, $next, ...$roles)` |
| `resources/views/components/layout.blade.php` | Базовый шаблон интерфейса | Рендеринг HTML, CSS-стилизация, вывод flash-уведомлений |
| `tests/Feature/NewsPagesTest.php` | Набор автоматических тестов | Проверка всех 4 страниц, фильтрации, защиты ролей и автоисцеления базы |
