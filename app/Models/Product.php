<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id',
        'name',
        'price',
        'price_per_unit',
        'image_url',
        'url',
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}
