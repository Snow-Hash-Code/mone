<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\VideoResource;
use App\Http\Resources\VideoCollection;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new VideoCollection(Video::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'data.attributes.title' => ['required'],
        'data.attributes.description' => ['required'],
        'data.attributes.slug' => ['required', 'unique:videos,slug'],
        'data.attributes.user_id' => ['required', 'exists:users,id'],
        'data.relationships.category.data.id' => ['required', 'exists:categories,id'],
    ]);

    $videoData = $request->input('data.attributes');

    $video = Video::create([
        'title' => $videoData['title'],
        'description' => $videoData['description'],
        'slug' => $videoData['slug'],
        'user_id' => $videoData['user_id'],
        'category_id' => $request->input('data.relationships.category.data.id'),
    ]);

    return VideoResource::make($video);
}

    




    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return new VideoResource($video);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'slug' => ['required', 'unique:videos,slug,' . $video->id],
        ]);

        $video->update($request->all());

        return new VideoResource($video);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return response()->json(null, 204);
    }

    public function put(Request $request, $id)
{
    // Validar los datos
    $request->validate([
        'data.attributes.title' => ['required'],
        'data.attributes.description' => ['required'],
        'data.attributes.slug' => ['required', 'unique:videos,slug'],
        // Agrega más reglas de validación según sea necesario
    ]);

    // Obtener el video por su ID
    $video = Video::find($id);

    // Verificar si se encontró el video
    if (!$video) {
        return response()->json(['error' => 'Video no encontrado'], 404);
    }

    // Actualizar los datos del video
    $video->update([
        'title' => $request->input('data.attributes.title'),
        'description' => $request->input('data.attributes.description'),
        'slug' => $request->input('data.attributes.slug'),
        // Actualiza más campos según sea necesario
    ]);

    return VideoResource::make($video);
}

// Método para manejar solicitudes PATCH a la ruta '/videos'
public function patch(Request $request, $id)
{
    // Validar los datos
    $request->validate([
        'data.attributes.title' => ['sometimes'],
        'data.attributes.description' => ['sometimes'],
        'data.attributes.slug' => ['sometimes', 'unique:videos,slug'],
        // Agrega más reglas de validación según sea necesario
    ]);

    // Obtener el video por su ID
    $video = Video::find($id);

    // Verificar si se encontró el video
    if (!$video) {
        return response()->json(['error' => 'Video no encontrado'], 404);
    }

    // Actualizar solo los campos proporcionados en la solicitud
    $video->update($request->input('data.attributes'));

    return VideoResource::make($video);
}

}
