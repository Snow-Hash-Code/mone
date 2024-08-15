<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'category_id' => 'integer',
    ];

    // Propiedades fillable
    protected $fillable = [
        'title', 'message', 'slug', 'user_id', 'category_id'
    ];

    // Relación con el usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con la categoría
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relación para obtener el último comentario
    public function comment(): MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable')->latestOfMany();
    }

    // Método para crear un post
    public static function createPost($postData, $categoryId)
    {
        return self::create([
            'title' => $postData['title'],
            'message' => $postData['message'],
            'slug' => $postData['slug'],
            'user_id' => $postData['user_id'],
            'category_id' => $categoryId,
        ]);
    }
}
