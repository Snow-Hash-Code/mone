<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Blueprint\Models\Index;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas para usuarios
Route::get('users/{user}', [UserController::class, 'show'])->name('api.users.show');
Route::get('users', [UserController::class, 'index'])->name('api.users.index');
Route::post('users', [UserController::class, 'store'])->name('api.users.store');
Route::delete('users/{user}', [UserController::class, 'destroy'])->name('api.users.destroy');
Route::put('users/{id}', [UserController::class, 'put'])->name('api.users.put');
Route::patch('users/{id}', [UserController::class, 'patch'])->name('api.users.patch');

// Rutas para videos
Route::get('videos', [VideoController::class, 'index'])->name('api.videos.index');
Route::post('videos', [VideoController::class, 'store'])->name('api.videos.store');
Route::get('videos/{video}', [VideoController::class, 'show'])->name('api.videos.show');
Route::put('videos/{video}', [VideoController::class, 'update'])->name('api.videos.update');
Route::delete('videos/{video}', [VideoController::class, 'destroy'])->name('api.videos.destroy');
Route::put('videos/{video}', [VideoController::class, 'put'])->name('api.videos.put');
Route::patch('videos/{video}', [VideoController::class, 'patch'])->name('api.videos.patch');

// Rutas para Auth
Route::post('register', [AuthController::class, 'store'])->name('api.users.register');
Route::post('login', [AuthController::class, 'login'])->name('api.users.login');

// Rutas para posts
Route::get('posts/{post}', [PostController::class, 'show'])->name('api.posts.show');
Route::get('posts', [PostController::class, 'index'])->name('api.posts.index');
Route::post('posts', [PostController::class, 'store'])->name('api.posts.store');
Route::delete('/posts/{post}', [PostController::class, 'delete'])->name('api.posts.delete');
Route::put('posts/{post}', [PostController::class, 'update'])->name('api.posts.update');



// Ruta para mostrar comentarios de un post
Route::get('posts/{post}/comments', [PostController::class, 'showComments'])->name('api.posts.comments.index');

// Rutas para comentarios
Route::get('comments', [CommentController::class, 'index'])->name('api.comments.index');
Route::get('comments/{comment}', [CommentController::class, 'show'])->name('api.comments.show');

//Categorias: 
Route::get('/categories', [CategoryController::class, 'index']);




// Ruta de prueba para obtener el usuario autenticado



//Route::middleware('guest')->post('ruta', [AuthController::class, 'store'])->name('api.v1.users.store');



// Route::get('/user/{id}', function (string $id) {
//     return new UserResource(User::findOrFail($id));
// });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
