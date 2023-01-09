<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description'
    ];

    public function scopeSearch(Builder|self $query, string $search)
    {
        $search = strtolower("%$search%");
        $query->where('title', 'like', $search)
            ->orWhere('description', 'like', $search);
    }
}
