<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    protected $fillable = [
        'course_id', 'title', 'description', 'position',
        'video_path', 'video_original_name', 'video_mime', 'video_size',
        'video_status', 'video_error',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function isReady(): bool
    {
        return $this->video_status === 'ready' && $this->video_path;
    }

    public function deleteVideoFile(): void
    {
        if ($this->video_path && Storage::disk('local')->exists($this->video_path)) {
            Storage::disk('local')->delete($this->video_path);
        }
    }
}
