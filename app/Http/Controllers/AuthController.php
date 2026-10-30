<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Показ формы входа
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    // Обработка входа пользователя
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Если это один из тестовых аккаунтов, проверяем его наличие (автовосстановление)
        $roleMap = [
            'admin@test.ru' => 'admin',
            'journalist@test.ru' => 'journalist',
            'user@test.ru' => 'user',
        ];
        if (isset($roleMap[$credentials['email']])) {
            $this->ensureUserExists($roleMap[$credentials['email']]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Добро пожаловать, '.Auth::user()->name.'!');
        }

        return back()->withErrors([
            'email' => 'Неверный адрес электронной почты или пароль.',
        ])->onlyInput('email');
    }

    // Показ формы регистрации
    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }

    // Регистрация нового читателя
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // По умолчанию регистрируется как читатель
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно! Вы вошли как '.$user->name);
    }

    // Выход из системы
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Вы успешно вышли из системы.');
    }

    // Быстрый вход для преподавателя (1 клик для проверки ролей)
    public function quickLogin(string $role)
    {
        $user = $this->ensureUserExists($role);

        if ($user) {
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Вы мгновенно вошли как: '.$user->role_title.' ('.$user->name.')');
        }

        return redirect()->route('login')->with('error', 'Не удалось инициализировать пользователя.');
    }

    /**
     * Автоматическое создание/проверка системных пользователей (защита от пустой базы в колледже).
     */
    private function ensureUserExists(string $role): ?User
    {
        $defaultUsers = [
            'admin' => [
                'name' => 'Администратор сайта',
                'email' => 'admin@test.ru',
                'role' => 'admin',
            ],
            'journalist' => [
                'name' => 'Иван Журналист',
                'email' => 'journalist@test.ru',
                'role' => 'journalist',
            ],
            'user' => [
                'name' => 'Алексей Читатель',
                'email' => 'user@test.ru',
                'role' => 'user',
            ],
        ];

        if (! isset($defaultUsers[$role])) {
            return null;
        }

        try {
            $user = User::where('email', $defaultUsers[$role]['email'])->first();
            if ($user) {
                return $user;
            }
        } catch (\Throwable $e) {
            // Если таблиц еще нет в SQLite — накатываем миграции на лету
            try {
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Throwable $mError) {
                // Игнорируем
            }
        }

        // Пытаемся запустить сидер или найти пользователя
        try {
            Artisan::call('db:seed', ['--force' => true]);
            $user = User::where('email', $defaultUsers[$role]['email'])->first();
            if ($user) {
                return $user;
            }
        } catch (\Throwable $sError) {
            // Игнорируем
        }

        // Если сидер не сработал — гарантированно создаем запись вручную
        try {
            return User::firstOrCreate(
                ['email' => $defaultUsers[$role]['email']],
                [
                    'name' => $defaultUsers[$role]['name'],
                    'password' => Hash::make('12345'),
                    'role' => $defaultUsers[$role]['role'],
                ]
            );
        } catch (\Throwable $cError) {
            return null;
        }
    }
}
