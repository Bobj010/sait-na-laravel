<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Проверка, является ли пользователь администратором
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Проверка, является ли пользователь журналистом (или админом с полным доступом)
    public function isJournalist(): bool
    {
        return in_array($this->role, ['journalist', 'admin']);
    }

    // Красивое название роли на русском языке
    public function getRoleTitleAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Администратор',
            'journalist' => 'Журналист',
            default => 'Читатель',
        };
    }

    /**
     * Связь: Пользователь может иметь много созданных новостей (1:N)
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
}
