<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin',
        ]);
        User::updateOrCreate(['email' => 'student@example.com'], [
            'name' => 'Student', 'password' => Hash::make('password'), 'role' => 'student',
        ]);

        Course::firstOrCreate(['title' => 'Pengantar Laravel'], [
            'description' => 'Course contoh. Tambahkan lesson dan upload video lewat panel admin.',
            'status' => 'published',
        ]);
    }
}
