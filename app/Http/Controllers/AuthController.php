<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        $emailMap = [
            'admin' => 'admin@test.ru',
            'journalist' => 'journalist@test.ru',
            'user' => 'user@test.ru',
        ];

        if (! isset($emailMap[$role])) {
            return redirect()->route('home')->with('error', 'Неизвестная роль.');
        }

        $user = User::where('email', $emailMap[$role])->first();

        if ($user) {
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Вы мгновенно вошли как: '.$user->role_title.' ('.$user->name.')');
        }

        return redirect()->route('login')->with('error', 'Пользователь не найден. Запустите сидер базы данных.');
    }
}
