<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::published()->withCount('lessons')->latest()->get();
        return view('student.index', compact('courses'));
    }

    public function show(Course $course)
    {
        abort_unless($course->isPublished(), 404);
        $course->load('lessons');
        return view('student.show', compact('course'));
    }

    public function lesson(Course $course, Lesson $lesson)
    {
        abort_unless($course->isPublished(), 404);
        abort_unless($lesson->course_id === $course->id, 404);
        $course->load('lessons');
        return view('student.lesson', compact('course', 'lesson'));
    }
}
