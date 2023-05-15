<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'site',
        'generation_date',
        'items_count',
    ];

    public function pornstars()
    {
        return $this->hasMany(Pornstar::class);
    }
}
