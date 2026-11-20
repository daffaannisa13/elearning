<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoStreamController extends Controller
{
    // Video disimpan di storage privat; hanya bisa diakses lewat route ini.
    // BinaryFileResponse mendukung HTTP Range sehingga video bisa di-seek.
    public function show(Request $request, Lesson $lesson)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            // Student hanya boleh melihat video dari course yang sudah published
            abort_unless($lesson->course->isPublished(), 403);
        }

        abort_unless($lesson->isReady(), 404, 'Video belum tersedia.');
        abort_unless(Storage::disk('local')->exists($lesson->video_path), 404);

        return response()->file(
            Storage::disk('local')->path($lesson->video_path),
            ['Content-Type' => $lesson->video_mime ?: 'video/mp4', 'Cache-Control' => 'private, max-age=0']
        );
    }
}
