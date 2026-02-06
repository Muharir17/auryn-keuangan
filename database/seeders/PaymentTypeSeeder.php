<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $paymentTypes = [
            [
                'code' => 'SPP',
                'name' => 'SPP Bulanan',
                'description' => 'Sumbangan Pembinaan Pendidikan - Biaya bulanan',
                'default_amount' => 500000,
                'frequency' => 'monthly',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'BOOK',
                'name' => 'Buku Pelajaran',
                'description' => 'Biaya buku pelajaran dan LKS',
                'default_amount' => 750000,
                'frequency' => 'yearly',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'EXTRA',
                'name' => 'Ekstrakurikuler',
                'description' => 'Biaya kegiatan ekstrakurikuler',
                'default_amount' => 200000,
                'frequency' => 'monthly',
                'is_mandatory' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'code' => 'SPECIAL',
                'name' => 'Kegiatan Khusus',
                'description' => 'Biaya untuk kegiatan khusus seperti study tour, camping, dll',
                'default_amount' => 0,
                'frequency' => 'custom',
                'is_mandatory' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'code' => 'UNIFORM',
                'name' => 'Seragam',
                'description' => 'Biaya seragam sekolah',
                'default_amount' => 500000,
                'frequency' => 'yearly',
                'is_mandatory' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($paymentTypes as $typeData) {
            PaymentType::updateOrCreate(
                ['code' => $typeData['code']],
                $typeData
            );
        }
    }
}
