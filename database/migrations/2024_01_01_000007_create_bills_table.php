<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->foreignId('student_id')->constrained()->onDelete('restrict');
            $table->foreignId('payment_type_id')->constrained()->onDelete('restrict');
            $table->integer('month')->nullable();
            $table->integer('year');
            $table->decimal('amount', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('final_amount', 15, 2);
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'partial', 'paid', 'waived'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'year', 'month']);
            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
