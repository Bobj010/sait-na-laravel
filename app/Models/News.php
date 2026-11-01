<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    // Разрешенные поля для массового заполнения
    protected $fillable = [
        'title',
        'category_id',
        'user_id',
        'content',
    ];

    /**
     * Связь: Новость принадлежит конкретной категории (N:1)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Связь: Новость принадлежит пользователю-автору (N:1)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
