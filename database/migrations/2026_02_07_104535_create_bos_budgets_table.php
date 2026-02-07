<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bos_budgets', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->decimal('amount', 15, 2);
            $table->decimal('used', 15, 2)->default(0);
            $table->decimal('remaining', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bos_budgets');
    }
};
