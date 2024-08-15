<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Video;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        public function run(): void
        {
            // Crear usuarios
            User::factory(10)->create();
    
            // Crear videos
            Video::factory(10)->create();
    
            // Crear categorías
            Category::factory(5)->create();
    
            // Crear publicaciones y asociar comentarios
           /* Post::factory(5)->create();
            Comment::factory(5)->create();*/


            Post::factory(5)->create()->each(function ($post) {
                // Crear entre 1 y 5 comentarios para cada publicación
                Comment::factory(random_int(1, 5))->create([
                    'commentable_id' => $post->id,
                    'commentable_type' => Post::class,
                ]);
            });
        }
    
}
