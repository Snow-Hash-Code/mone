<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'data.attributes.name' => ['required', 'string', 'min:4'],
            'data.attributes.email' => ['required', 'email', 'unique:users,email'],
            'data.attributes.password' => ['required', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->input('data.attributes.name'),
            'email' => $request->input('data.attributes.email'),
            'password' => Hash::make($request->input('data.attributes.password')), // Hashed password
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'type' => 'users',
                'id' => (string)$user->id,
                'attributes' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'token' => $token,
                'links' => [
                    'self' => url("/api/users/{$user->id}"),
                ],
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'data.attributes.email' => ['required', 'email', 'exists:users,email'],
            'data.attributes.password' => ['required'],
        ]);

        $credentials = [
            'email' => $request->input('data.attributes.email'),
            'password' => $request->input('data.attributes.password'),
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24); // Expira en 1 día
            return response()->json([
                'data' => [
                    'type' => 'users',
                    'id' => (string)$user->id,
                    'attributes' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                    'token' => $token,
                    'links' => [
                        'self' => url("/api/users/{$user->id}"),
                    ],
                ],
            ], Response::HTTP_OK)->withCookie($cookie);
        } else {
            return response()->json(['message' => 'Credenciales inválidas'], Response::HTTP_UNAUTHORIZED);
        }
    }
}


