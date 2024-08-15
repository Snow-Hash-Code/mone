<?php

namespace Tests\Feature\Video;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListVideosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_video(): void
    {
        $this->withoutExceptionHandling();

        $video = Video::factory()->create();

        $response = $this->getJson(route('api.videos.show', $video));

        $response->assertJson([
            'data' => [
                'type' => 'videos',
                'id' => (string)$video->id,
                'attributes' => [
                    'title' => $video->title,
                    'description' => $video->description,
                    'slug' => $video->slug,
                    // Agrega más atributos si es necesario
                ],
                'links' => [
                    'self' => route('api.videos.show', $video),
                ]
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_videos(): void
    {
        $this->withoutExceptionHandling();

        $videos = Video::factory()->count(3)->create();
        $expectedData = $videos->map(function ($video) {
            return [
                'type' => 'videos',
                'id' => (string)$video->id,
                'attributes' => [
                    'title' => $video->title,
                    'description' => $video->description,
                    'slug' => $video->slug,
                    // Agrega más atributos si es necesario
                ],
                'links' => [
                    'self' => route('api.videos.show', $video),
                ]
            ];
        })->toArray();

        $response = $this->getJson(route('api.videos.index'));

        $response->assertJson([
            'data' => $expectedData,
            'links' => [
                'self' => route('api.videos.index')
            ]
        ]);
    }

    /** @test */
    public function can_create_a_profile(): void
    {
        $this->withoutExceptionHandling();

        $users = User::factory()->count(3)->create();
        $expectedData = $users->map(function ($user) {
            return [
                'type' => 'users',
                'attributes' => [
                    // 'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->toJSON(),
                    'updated_at' => $user->updated_at->toJSON(),
                    'password' => $user->password,
                    // Agrega más atributos si es necesario
                ],
                'links' => [
                    'self' => route('api.users.show', $user),
                ]
            ];
        })->toArray();

        $response = $this->getJson(route('api.users.index'));

        $response->assertJson([
            'data' => $expectedData,
            'links' => [
                'self' => route('api.users.index')
            ]
        ]);
    }
}
