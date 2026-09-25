<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'photo',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_fields',
    ];

    protected $casts = [
        'meta_fields' => 'array',
    ];
}
