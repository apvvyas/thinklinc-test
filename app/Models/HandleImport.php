<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandleImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_published_at_proccessed', 'youtube_id'
    ];

    protected $casts = [
        'last_published_at_proccessed' => 'datetime'
    ];
}
