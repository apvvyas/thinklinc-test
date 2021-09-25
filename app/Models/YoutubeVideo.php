<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class YoutubeVideo extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'youtube_id', 'title', 'description', 'published_at', 'thumbnails', 'etag'
    ];

    protected $casts = [
        'thumbnails' => 'array',
        'published_at' => 'datetime'
    ];
}
