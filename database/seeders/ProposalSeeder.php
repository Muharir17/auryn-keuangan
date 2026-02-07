<?php

namespace Database\Seeders;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProposalSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@smpasm.sch.id')->first();
        $teacher = User::where('email', 'teacher@smpasm.sch.id')->first();

        // Pending proposals
        Proposal::create([
            'title' => 'Proposal Kegiatan Pramuka',
            'submitted_by' => $teacher->id ?? 1,
            'date' => now()->subDays(5),
            'amount' => 10000000,
            'description' => 'Proposal untuk kegiatan pramuka semester genap',
            'status' => 'pending',
        ]);

        Proposal::create([
            'title' => 'Proposal Study Tour',
            'submitted_by' => $teacher->id ?? 1,
            'date' => now()->subDays(3),
            'amount' => 50000000,
            'description' => 'Proposal study tour ke museum dan tempat bersejarah',
            'status' => 'pending',
        ]);

        Proposal::create([
            'title' => 'Proposal Lomba Sains',
            'submitted_by' => $teacher->id ?? 1,
            'date' => now()->subDays(2),
            'amount' => 15000000,
            'description' => 'Proposal untuk mengikuti lomba sains tingkat provinsi',
            'status' => 'pending',
        ]);

        // Approved proposals
        Proposal::create([
            'title' => 'Proposal Pelatihan Komputer',
            'submitted_by' => $teacher->id ?? 1,
            'date' => now()->subDays(30),
            'amount' => 20000000,
            'description' => 'Proposal pelatihan komputer untuk siswa',
            'status' => 'approved',
            'approved_by' => $admin->id ?? 1,
            'approved_at' => now()->subDays(25),
        ]);

        Proposal::create([
            'title' => 'Proposal Renovasi Perpustakaan',
            'submitted_by' => $teacher->id ?? 1,
            'date' => now()->subDays(45),
            'amount' => 35000000,
            'description' => 'Proposal renovasi dan pengadaan buku perpustakaan',
            'status' => 'approved',
            'approved_by' => $admin->id ?? 1,
            'approved_at' => now()->subDays(40),
        ]);

        // Rejected proposal
        Proposal::create([
            'title' => 'Proposal Wisata Luar Negeri',
            'submitted_by' => $teacher->id ?? 1,
            'date' => now()->subDays(20),
            'amount' => 100000000,
            'description' => 'Proposal wisata edukasi ke luar negeri',
            'status' => 'rejected',
            'approved_by' => $admin->id ?? 1,
            'approved_at' => now()->subDays(15),
            'rejection_reason' => 'Budget tidak mencukupi untuk tahun ini',
        ]);
    }
}
