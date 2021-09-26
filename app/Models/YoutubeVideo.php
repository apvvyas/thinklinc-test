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

    /**
     * Scope function to implement searchable and manage null
     * 
     * @param $query - QueryBuilder object
     * @param $search - Search params
     * 
     * @return $query or null
     */
    function scopeSearch($query, $search)
    {
        if(!empty($search)){
            return $query->search($search);
        }
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description
        ];
    }

    /**
     * Scope function for query building and finding a youtube vide by id or youtube_id
     * 
     * @param $query - object of the Query builder
     * @param $id - id/youtube_id of the elements
     * 
     * @return $query 
     */
    public function scopefindVideo($query,$id)
    {
        return $query->where('id', $id)->orWhere('youtube_id', $id);
    }
}
