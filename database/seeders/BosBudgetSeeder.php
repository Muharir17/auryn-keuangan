<?php

namespace Database\Seeders;

use App\Models\BosBudget;
use App\Models\BosTransaction;
use Illuminate\Database\Seeder;

class BosBudgetSeeder extends Seeder
{
    public function run(): void
    {
        // Budget 2024
        $budget2024 = BosBudget::create([
            'year' => 2024,
            'amount' => 500000000,
            'used' => 350000000,
            'remaining' => 150000000,
            'description' => 'Budget BOS Tahun 2024',
        ]);

        // Transactions for 2024
        BosTransaction::create([
            'bos_budget_id' => $budget2024->id,
            'date' => '2024-01-15',
            'description' => 'Pembelian ATK',
            'type' => 'expense',
            'amount' => 5000000,
            'category' => 'Perlengkapan',
        ]);

        BosTransaction::create([
            'bos_budget_id' => $budget2024->id,
            'date' => '2024-02-20',
            'description' => 'Perbaikan Gedung',
            'type' => 'expense',
            'amount' => 25000000,
            'category' => 'Infrastruktur',
        ]);

        // Budget 2025
        $budget2025 = BosBudget::create([
            'year' => 2025,
            'amount' => 600000000,
            'used' => 200000000,
            'remaining' => 400000000,
            'description' => 'Budget BOS Tahun 2025',
        ]);

        // Transactions for 2025
        BosTransaction::create([
            'bos_budget_id' => $budget2025->id,
            'date' => '2025-01-10',
            'description' => 'Pembelian Buku Pelajaran',
            'type' => 'expense',
            'amount' => 15000000,
            'category' => 'Pendidikan',
        ]);

        BosTransaction::create([
            'bos_budget_id' => $budget2025->id,
            'date' => '2025-02-05',
            'description' => 'Pelatihan Guru',
            'type' => 'expense',
            'amount' => 10000000,
            'category' => 'SDM',
        ]);

        // Budget 2026
        $budget2026 = BosBudget::create([
            'year' => 2026,
            'amount' => 750000000,
            'used' => 125000000,
            'remaining' => 625000000,
            'description' => 'Budget BOS Tahun 2026',
        ]);

        // Transactions for 2026
        BosTransaction::create([
            'bos_budget_id' => $budget2026->id,
            'date' => '2026-01-15',
            'description' => 'Pembelian Komputer',
            'type' => 'expense',
            'amount' => 50000000,
            'category' => 'Teknologi',
        ]);

        BosTransaction::create([
            'bos_budget_id' => $budget2026->id,
            'date' => '2026-02-01',
            'description' => 'Renovasi Lab',
            'type' => 'expense',
            'amount' => 75000000,
            'category' => 'Infrastruktur',
        ]);
    }
}
