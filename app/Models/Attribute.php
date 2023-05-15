<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'pornstar_id',
        'hair_color',
        'ethnicity',
        'tattoos',
        'piercings',
        'breast_size',
        'breast_type',
        'gender',
        'orientation',
        'age',
    ];

    public function pornstar()
    {
        return $this->belongsTo(Pornstar::class);
    }

    public function stats()
    {
        return $this->hasOne(Stat::class);
    }

}
