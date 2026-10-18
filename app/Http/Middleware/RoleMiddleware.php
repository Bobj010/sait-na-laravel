<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Обработка входящего запроса для проверки роли пользователя.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles  Список разрешенных ролей (например: 'admin', 'journalist')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Разделы «Журналист» и «Администратор» доступны сотрудникам редакции. Все посетители сайта по умолчанию являются читателями.');
        }

        $user = auth()->user();

        // 2. Если роль пользователя входит в список разрешенных ролей — пропускаем дальше
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Если роли нет в списке — отдаем ошибку 403 (Доступ запрещен)
        abort(403, 'Доступ запрещен. У вашей роли ('.$user->role_title.') нет прав для просмотра этого раздела.');
    }
}
