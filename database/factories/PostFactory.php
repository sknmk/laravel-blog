<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::role('author')->get()->random()->id,
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(3),
            'content' => $this->faker->paragraph(50),
            'published_at' => date('Y-m-d H:i:s')
        ];
    }
}
