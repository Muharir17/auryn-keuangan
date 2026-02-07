<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'Administrator',
        ]);

        Bouncer::role()->firstOrCreate([
            'name' => 'teacher',
            'title' => 'Guru/Wali Kelas',
        ]);

        Bouncer::role()->firstOrCreate([
            'name' => 'finance',
            'title' => 'Bendahara',
        ]);

        Bouncer::role()->firstOrCreate([
            'name' => 'principal',
            'title' => 'Kepala Sekolah',
        ]);

        Bouncer::role()->firstOrCreate([
            'name' => 'foundation',
            'title' => 'Yayasan',
        ]);

        // Create abilities/permissions
        $abilities = [
            // User Management
            'manage-users' => 'Kelola Users',
            'manage-roles' => 'Kelola Roles',
            'manage-permissions' => 'Kelola Permissions',

            // Data Management
            'manage-classes' => 'Kelola Kelas',
            'manage-students' => 'Kelola Siswa',
            'view-students' => 'Lihat Siswa',

            // Financial Management
            'manage-payment-types' => 'Kelola Jenis Pembayaran',
            'manage-bills' => 'Kelola Tagihan',
            'view-payments' => 'Lihat Pembayaran',
            'create-payments' => 'Buat Pembayaran',
            'validate-payments' => 'Validasi Pembayaran',
            'bulk-upload-payments' => 'Upload Pembayaran Massal',

            // Arrears Management
            'view-arrears' => 'Lihat Tunggakan',
            'manage-arrears' => 'Kelola Tunggakan',
            'adjust-arrears' => 'Sesuaikan Tunggakan',

            // Proposal Management
            'create-proposals' => 'Buat Proposal',
            'approve-proposals' => 'Setujui Proposal',
            'view-proposals' => 'Lihat Proposal',

            // BOS Management
            'manage-bos' => 'Kelola Dana BOS',
            'view-bos' => 'Lihat Dana BOS',

            // Reports
            'view-reports' => 'Lihat Laporan',
            'export-reports' => 'Export Laporan',

            // System
            'view-logs' => 'Lihat Log Sistem',
            'manage-settings' => 'Kelola Pengaturan',
        ];

        foreach ($abilities as $name => $title) {
            Bouncer::ability()->firstOrCreate([
                'name' => $name,
                'title' => $title,
            ]);
        }

        // Assign abilities to roles

        // Admin - Full access
        Bouncer::allow('admin')->everything();

        // Finance - Financial management
        Bouncer::allow('finance')->to([
            'view-students',
            'view-payments',
            'create-payments',
            'validate-payments',
            'bulk-upload-payments',
            'manage-payment-types',
            'manage-bills',
            'view-arrears',
            'manage-arrears',
            'adjust-arrears',
            'view-reports',
            'export-reports',
            'manage-bos',
            'view-bos',
        ]);

        // Teacher - Basic payment operations
        Bouncer::allow('teacher')->to([
            'view-students',
            'view-payments',
            'create-payments',
            'bulk-upload-payments',
            'view-arrears',
        ]);

        // Principal - View and approve
        Bouncer::allow('principal')->to([
            'view-students',
            'view-payments',
            'view-arrears',
            'view-proposals',
            'approve-proposals',
            'view-reports',
            'export-reports',
            'view-bos',
        ]);

        // Foundation - High-level view and approval
        Bouncer::allow('foundation')->to([
            'view-students',
            'view-payments',
            'view-arrears',
            'view-proposals',
            'approve-proposals',
            'view-reports',
            'export-reports',
            'view-bos',
        ]);

        $this->command->info('Bouncer roles and abilities seeded successfully!');
    }
}
