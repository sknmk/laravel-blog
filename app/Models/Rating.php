<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'description'
    ];

    public function posts(): BelongsToMany {
        return $this->belongsToMany(Post::class);
    }
}
