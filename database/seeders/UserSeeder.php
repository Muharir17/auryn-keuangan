<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $financeRole = Role::where('name', 'finance')->first();
        $principalRole = Role::where('name', 'principal')->first();
        $foundationRole = Role::where('name', 'foundation')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@smpasm.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );
        $admin->roles()->sync([$adminRole->id]);

        $finance = User::updateOrCreate(
            ['email' => 'finance@smpasm.sch.id'],
            [
                'name' => 'Bendahara',
                'password' => Hash::make('password'),
                'phone' => '081234567891',
                'is_active' => true,
            ]
        );
        $finance->roles()->sync([$financeRole->id]);

        $principal = User::updateOrCreate(
            ['email' => 'principal@smpasm.sch.id'],
            [
                'name' => 'Kepala Sekolah',
                'password' => Hash::make('password'),
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
        $principal->roles()->sync([$principalRole->id]);

        $foundation = User::updateOrCreate(
            ['email' => 'foundation@smpasm.sch.id'],
            [
                'name' => 'Yayasan',
                'password' => Hash::make('password'),
                'phone' => '081234567893',
                'is_active' => true,
            ]
        );
        $foundation->roles()->sync([$foundationRole->id]);

        $teacher1 = User::updateOrCreate(
            ['email' => 'teacher1@smpasm.sch.id'],
            [
                'name' => 'Guru Wali Kelas 7A',
                'password' => Hash::make('password'),
                'phone' => '081234567894',
                'is_active' => true,
            ]
        );
        $teacher1->roles()->sync([$teacherRole->id]);

        $teacher2 = User::updateOrCreate(
            ['email' => 'teacher2@smpasm.sch.id'],
            [
                'name' => 'Guru Wali Kelas 8A',
                'password' => Hash::make('password'),
                'phone' => '081234567895',
                'is_active' => true,
            ]
        );
        $teacher2->roles()->sync([$teacherRole->id]);
    }
}
