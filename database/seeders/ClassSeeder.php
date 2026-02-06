<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $teacher1 = User::where('email', 'teacher1@smpasm.sch.id')->first();
        $teacher2 = User::where('email', 'teacher2@smpasm.sch.id')->first();

        $classes = [
            [
                'name' => '7A',
                'level' => 7,
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => $teacher1?->id,
                'student_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => '7B',
                'level' => 7,
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => null,
                'student_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => '8A',
                'level' => 8,
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => $teacher2?->id,
                'student_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => '8B',
                'level' => 8,
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => null,
                'student_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => '9A',
                'level' => 9,
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => null,
                'student_count' => 0,
                'is_active' => true,
            ],
            [
                'name' => '9B',
                'level' => 9,
                'academic_year' => '2024/2025',
                'homeroom_teacher_id' => null,
                'student_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($classes as $classData) {
            ClassRoom::updateOrCreate(
                ['name' => $classData['name'], 'academic_year' => $classData['academic_year']],
                $classData
            );
        }
    }
}
