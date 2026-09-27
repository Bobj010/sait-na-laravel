<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    // Разрешенные поля для массового заполнения
    protected $fillable = [
        'title',
        'category',
        'content',
    ];
}
