<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }

    public function index()
    {
        $courses = Course::withCount('lessons')->latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $course = Course::create($request->validate($this->rules()));
        return redirect()->route('admin.courses.show', $course)->with('success', 'Course berhasil dibuat. Silakan tambahkan lesson.');
    }

    // Halaman kelola lesson (tambah, ubah, hapus, urutkan, upload video)
    public function show(Course $course)
    {
        $course->load('lessons');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $course->update($request->validate($this->rules()));
        return redirect()->route('admin.courses.index')->with('success', 'Course berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        foreach ($course->lessons as $lesson) {
            $lesson->deleteVideoFile();
        }
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course berhasil dihapus.');
    }
}
