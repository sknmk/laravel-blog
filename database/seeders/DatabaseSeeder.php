<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            PermissionSeeder::class,
            RatingSeeder::class
        ]);
        Post::query()->delete();
        User::query()->delete();
        User::factory(1)
            ->create()
            ->each(function ($user) {
                $user->assignRole('admin');
            });
        User::factory(1)
            ->create()
            ->each(function ($user) {
                $user->assignRole('moderator');
            });
        User::factory(3)
            ->create()
            ->each(function ($user) {
                $user->assignRole('author');
            });
        User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->assignRole('reader');
            });
        Post::factory(30)->create();
    }
}
