<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $course->lessons()->create($data + [
            'position' => ($course->lessons()->max('position') ?? 0) + 1,
        ]);

        return back()->with('success', 'Lesson ditambahkan. Silakan upload videonya.');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $lesson->update($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]));

        return back()->with('success', 'Lesson diperbarui.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->deleteVideoFile();
        $lesson->delete();

        return back()->with('success', 'Lesson dihapus.');
    }

    // Simpan urutan baru hasil drag & drop: order = [lessonId, lessonId, ...]
    public function reorder(Request $request, Course $course)
    {
        $order = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ])['order'];

        $valid = $course->lessons()->pluck('id')->all();

        foreach (array_values($order) as $i => $id) {
            if (in_array($id, $valid, true)) {
                Lesson::where('id', $id)->where('course_id', $course->id)->update(['position' => $i + 1]);
            }
        }

        return response()->json(['ok' => true]);
    }

    // Tukar posisi dengan lesson di atas / di bawahnya
    public function move(Request $request, Lesson $lesson)
    {
        $direction = $request->validate(['direction' => ['required', 'in:up,down']])['direction'];

        $query = Lesson::where('course_id', $lesson->course_id);
        $neighbor = $direction === 'up'
            ? $query->where('position', '<', $lesson->position)->orderByDesc('position')->first()
            : $query->where('position', '>', $lesson->position)->orderBy('position')->first();

        if ($neighbor) {
            [$a, $b] = [$lesson->position, $neighbor->position];
            $lesson->update(['position' => $b]);
            $neighbor->update(['position' => $a]);
        }

        return back();
    }
}
