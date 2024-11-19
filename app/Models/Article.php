<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'author',
        'source',
        'category',
        'url',
        'published_at',
    ];
    protected function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
