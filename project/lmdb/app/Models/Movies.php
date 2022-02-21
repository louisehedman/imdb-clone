<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    protected $table = 'movies';
    protected $fillable = [
        'id',
        'title',
        'description',
        'genre',
        'release_date',
        'image_path',
        'created_at',
        'updated_at',
    ];
}
