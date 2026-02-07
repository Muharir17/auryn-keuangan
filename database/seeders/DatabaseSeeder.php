<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BouncerSeeder::class,  // Bouncer roles and abilities
            UserSeeder::class,
            PaymentTypeSeeder::class,
            ClassSeeder::class,
            StudentSeeder::class,
            BosBudgetSeeder::class,
            ProposalSeeder::class,
        ]);
    }
}
