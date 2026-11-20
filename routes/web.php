<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student;
use App\Http\Controllers\VideoStreamController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.courses.index')
            : redirect()->route('student.courses.index');
    })->name('dashboard');

    // Stream video (otorisasi dicek di controller)
    Route::get('/lessons/{lesson}/video', [VideoStreamController::class, 'show'])->name('lessons.video');
});

// ---------- ADMIN ----------
Route::middleware(['auth', RoleMiddleware::class . ':admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::resource('courses', Admin\CourseController::class);

        Route::post('courses/{course}/lessons', [Admin\LessonController::class, 'store'])->name('lessons.store');
        Route::put('lessons/{lesson}', [Admin\LessonController::class, 'update'])->name('lessons.update');
        Route::delete('lessons/{lesson}', [Admin\LessonController::class, 'destroy'])->name('lessons.destroy');
        Route::post('lessons/{lesson}/move', [Admin\LessonController::class, 'move'])->name('lessons.move');
        Route::post('lessons/reorder', [Admin\LessonController::class, 'reorder'])->name('lessons.reorder');

        Route::post('lessons/{lesson}/video', [Admin\VideoController::class, 'upload'])->name('lessons.video.upload');
        Route::delete('lessons/{lesson}/video', [Admin\VideoController::class, 'destroy'])->name('lessons.video.destroy');
    });

// ---------- STUDENT ----------
Route::middleware(['auth', RoleMiddleware::class . ':student'])
    ->prefix('student')->name('student.')->group(function () {
        Route::get('courses', [Student\CourseController::class, 'index'])->name('courses.index');
        Route::get('courses/{course}', [Student\CourseController::class, 'show'])->name('courses.show');
        Route::get('courses/{course}/lessons/{lesson}', [Student\CourseController::class, 'lesson'])->name('courses.lesson');
    });
