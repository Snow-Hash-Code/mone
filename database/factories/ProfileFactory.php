<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Profile;
use App\Models\User;

class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'instagram' => $this->faker->word(),
            'github' => $this->faker->word(),
            'web' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'hobbies' => $this->faker->word(),
            'description' => $this->faker->text(),
            'user_id' => User::factory(),
        ];
    }
}
