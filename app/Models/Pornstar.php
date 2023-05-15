<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pornstar extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_id',
        'name',
        'license',
        'wl_status',
        'link',
        'aliases'
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function attributes()
    {
        return $this->hasOne(Attribute::class);
    }

    public function thumbnails()
    {
        return $this->hasMany(Thumbnail::class);
    }
}
