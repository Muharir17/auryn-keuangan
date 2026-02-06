<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $class7A = ClassRoom::where('name', '7A')->where('academic_year', '2024/2025')->first();
        $class8A = ClassRoom::where('name', '8A')->where('academic_year', '2024/2025')->first();

        if (!$class7A || !$class8A) {
            $this->command->warn('Classes not found. Please run ClassSeeder first.');
            return;
        }

        $students = [
            [
                'nis' => '2024001',
                'nisn' => '0123456789',
                'name' => 'Ahmad Fauzi',
                'class_id' => $class7A->id,
                'gender' => 'L',
                'birth_date' => '2011-05-15',
                'birth_place' => 'Jakarta',
                'address' => 'Jl. Merdeka No. 123',
                'parent_name' => 'Budi Santoso',
                'parent_phone' => '081234567801',
                'parent_email' => 'budi@email.com',
                'enrollment_date' => '2024-07-01',
                'status' => 'active',
            ],
            [
                'nis' => '2024002',
                'nisn' => '0123456790',
                'name' => 'Siti Nurhaliza',
                'class_id' => $class7A->id,
                'gender' => 'P',
                'birth_date' => '2011-08-20',
                'birth_place' => 'Bandung',
                'address' => 'Jl. Sudirman No. 456',
                'parent_name' => 'Hasan Basri',
                'parent_phone' => '081234567802',
                'parent_email' => 'hasan@email.com',
                'enrollment_date' => '2024-07-01',
                'status' => 'active',
            ],
            [
                'nis' => '2024003',
                'nisn' => '0123456791',
                'name' => 'Muhammad Rizki',
                'class_id' => $class7A->id,
                'gender' => 'L',
                'birth_date' => '2011-03-10',
                'birth_place' => 'Surabaya',
                'address' => 'Jl. Gatot Subroto No. 789',
                'parent_name' => 'Agus Wijaya',
                'parent_phone' => '081234567803',
                'parent_email' => 'agus@email.com',
                'enrollment_date' => '2024-07-01',
                'status' => 'active',
            ],
            [
                'nis' => '2023001',
                'nisn' => '0123456792',
                'name' => 'Dewi Lestari',
                'class_id' => $class8A->id,
                'gender' => 'P',
                'birth_date' => '2010-11-25',
                'birth_place' => 'Yogyakarta',
                'address' => 'Jl. Ahmad Yani No. 321',
                'parent_name' => 'Sutrisno',
                'parent_phone' => '081234567804',
                'parent_email' => 'sutrisno@email.com',
                'enrollment_date' => '2023-07-01',
                'status' => 'active',
            ],
            [
                'nis' => '2023002',
                'nisn' => '0123456793',
                'name' => 'Andi Pratama',
                'class_id' => $class8A->id,
                'gender' => 'L',
                'birth_date' => '2010-09-18',
                'birth_place' => 'Semarang',
                'address' => 'Jl. Diponegoro No. 654',
                'parent_name' => 'Bambang Hermawan',
                'parent_phone' => '081234567805',
                'parent_email' => 'bambang@email.com',
                'enrollment_date' => '2023-07-01',
                'status' => 'active',
            ],
        ];

        foreach ($students as $studentData) {
            Student::updateOrCreate(
                ['nis' => $studentData['nis']],
                $studentData
            );
        }

        $class7A->update(['student_count' => $class7A->students()->count()]);
        $class8A->update(['student_count' => $class8A->students()->count()]);
    }
}
