<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PostController extends Controller
{
    public function show(Post $post)
    {
        // Cargar relaciones user y comment
        $post->load(['user', 'comment.user']);
        return new PostResource($post);
    }

    public function index()
    {
        // Cargar relaciones user y comment
        $posts = Post::with(['user', 'comment.user'])->get();
        return PostResource::collection($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'data.attributes.title' => ['required'],
            'data.attributes.message' => ['required'],
            'data.attributes.slug' => ['required', 'unique:posts,slug'],
            'data.attributes.user_id' => ['required', 'exists:users,id'],
            'data.relationships.category.data.id' => ['required', 'exists:categories,id'],
        ]);

        $postData = $request->input('data.attributes');
        $categoryId = $request->input('data.relationships.category.data.id');

        $post = Post::create([
            'title' => $postData['title'],
            'message' => $postData['message'],
            'slug' => $postData['slug'],
            'user_id' => $postData['user_id'],
            'category_id' => $categoryId,
        ]);

        // Devolver el mensaje y los datos del post
        return response()->json([
            'post' => $post
        ], 201);
    }

    public function delete(Post $post)
    {
        // Verificar si el usuario tiene permisos para eliminar el post (opcional)

        // Eliminar el post
        $post->delete();

        // Devolver una respuesta indicando que el post ha sido eliminado
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request, Post $post)
    {
        // Validar los datos recibidos en la solicitud
        $request->validate([
            'data.attributes.title' => ['required'],
            'data.attributes.message' => ['required'],
            'data.attributes.slug' => ['required', 'unique:posts,slug,' . $post->id],
            'data.attributes.user_id' => ['required', 'exists:users,id'],
            'data.relationships.category.data.id' => ['required', 'exists:categories,id'],
        ]);

        // Actualizar los campos del post si están presentes en la solicitud
        if ($request->input('data.attributes.title')) {
            $post->title = $request->input('data.attributes.title');
        }
        if ($request->input('data.attributes.message')) {
            $post->message = $request->input('data.attributes.message');
        }
        if ($request->input('data.attributes.slug')) {
            $post->slug = $request->input('data.attributes.slug');
        }
        if ($request->input('data.attributes.user_id')) {
            $post->user_id = $request->input('data.attributes.user_id');
        }
        if ($request->input('data.relationships.category.data.id')) {
            $post->category_id = $request->input('data.relationships.category.data.id');
        }

        // Guardar los cambios en la base de datos
        $post->save();

        // Devolver la respuesta con los datos actualizados del post
        return new PostResource($post);
    }
}
