<?php

namespace Database\Seeders;

use App\Models\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0.5, 5, 0.5) as $rating) {
            Rating::create([
                'rating' => $rating,
                'description' => $rating
            ]);
        }
    }
}
