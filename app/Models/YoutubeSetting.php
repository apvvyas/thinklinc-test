<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoutubeSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key', 'expired_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    function setExpired(){

        $this->attributes['expired_at'] = Carbon::now();
        return $this;
    }

    function scopeNotExpired($query){
        return $query->whereNull('expired_at');
    }

    function scopeIsExpired($query){
        return $query->whereNotNull('expired_at');
    }
    
}
