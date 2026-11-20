<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class VideoController extends Controller
{
    // Dipanggil via XHR dari browser (dengan progress bar)
    public function upload(Request $request, Lesson $lesson)
    {
        // Hapus video lama, tandai status = uploading
        $lesson->deleteVideoFile();
        $lesson->update([
            'video_path' => null, 'video_original_name' => null,
            'video_mime' => null, 'video_size' => null,
            'video_status' => 'uploading', 'video_error' => null,
        ]);

        $validator = Validator::make($request->all(), [
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime', 'max:512000'], // 500 MB
        ], [
            'video.required' => 'File video tidak diterima (periksa batas upload_max_filesize / post_max_size PHP).',
            'video.mimetypes' => 'Format harus mp4, webm, ogg, atau mov.',
            'video.max' => 'Ukuran video maksimal 500 MB.',
            'video.uploaded' => 'Upload gagal (kemungkinan melebihi batas upload PHP).',
        ]);

        if ($validator->fails()) {
            return $this->fail($lesson, $validator->errors()->first(), 422);
        }

        try {
            $file = $request->file('video');
            $path = $file->store('videos', 'local'); // storage/app/videos (private)

            $lesson->update([
                'video_path' => $path,
                'video_original_name' => $file->getClientOriginalName(),
                'video_mime' => $file->getMimeType(),
                'video_size' => $file->getSize(),
                'video_status' => 'ready',
                'video_error' => null,
            ]);
        } catch (Throwable $e) {
            report($e);
            return $this->fail($lesson, 'Terjadi kesalahan saat menyimpan video.', 500);
        }

        return response()->json([
            'status' => 'ready',
            'name' => $lesson->video_original_name,
            'message' => 'Video berhasil diunggah.',
        ]);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->deleteVideoFile();
        $lesson->update([
            'video_path' => null, 'video_original_name' => null,
            'video_mime' => null, 'video_size' => null,
            'video_status' => 'none', 'video_error' => null,
        ]);

        return back()->with('success', 'Video dihapus.');
    }

    private function fail(Lesson $lesson, string $message, int $code)
    {
        $lesson->update(['video_status' => 'failed', 'video_error' => $message]);
        return response()->json(['status' => 'failed', 'message' => $message], $code);
    }
}
