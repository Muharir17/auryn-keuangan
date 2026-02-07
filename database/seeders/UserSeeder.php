<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@smpasm.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );
        Bouncer::assign('admin')->to($admin);

        // Create Finance
        $finance = User::updateOrCreate(
            ['email' => 'finance@smpasm.sch.id'],
            [
                'name' => 'Bendahara',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );
        Bouncer::assign('finance')->to($finance);

        // Create Principal
        $principal = User::updateOrCreate(
            ['email' => 'principal@smpasm.sch.id'],
            [
                'name' => 'Kepala Sekolah',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
        Bouncer::assign('principal')->to($principal);

        // Create Foundation
        $foundation = User::updateOrCreate(
            ['email' => 'foundation@smpasm.sch.id'],
            [
                'name' => 'Yayasan',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'is_active' => true,
            ]
        );
        Bouncer::assign('foundation')->to($foundation);

        // Create Teacher 1
        $teacher1 = User::updateOrCreate(
            ['email' => 'teacher1@smpasm.sch.id'],
            [
                'name' => 'Guru Wali Kelas 7A',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'is_active' => true,
            ]
        );
        Bouncer::assign('teacher')->to($teacher1);

        // Create Teacher 2
        $teacher2 = User::updateOrCreate(
            ['email' => 'teacher2@smpasm.sch.id'],
            [
                'name' => 'Guru Wali Kelas 8A',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
                'is_active' => true,
            ]
        );
        Bouncer::assign('teacher')->to($teacher2);

        $this->command->info('Users seeded successfully with Bouncer roles!');
    }
}
