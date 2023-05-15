<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pornstar_id',
        'height',
        'width',
        'type',
        'url'
    ];

    public function pornstar()
    {
        return $this->belongsTo(Pornstar::class);
    }

}
