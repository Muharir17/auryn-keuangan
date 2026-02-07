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
        // 1. Admin
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

        // 2. Finance (Bendahara)
        $finance = User::updateOrCreate(
            ['email' => 'finance@smpasm.sch.id'],
            [
                'name' => 'Siti Aminah, S.E', // Bendahara
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );
        Bouncer::assign('finance')->to($finance);

        // 3. Principal (Kepala Sekolah)
        $principal = User::updateOrCreate(
            ['email' => 'principal@smpasm.sch.id'],
            [
                'name' => 'Drs. H. Ahmad Dahlan', // Kepala Sekolah
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
        Bouncer::assign('principal')->to($principal);

        // 4. Foundation (Yayasan)
        $foundation = User::updateOrCreate(
            ['email' => 'foundation@smpasm.sch.id'],
            [
                'name' => 'Hj. Fatimah', // Yayasan
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'is_active' => true,
            ]
        );
        Bouncer::assign('foundation')->to($foundation);

        // 5. Teacher (Guru/Wali Kelas)
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@smpasm.sch.id'],
            [
                'name' => 'Budi Santoso, S.Pd',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'is_active' => true,
            ]
        );
        Bouncer::assign('teacher')->to($teacher);

        $this->command->info('Users seeded successfully with Bouncer roles!');
    }
}
