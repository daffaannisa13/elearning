<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['title', 'description', 'status'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
