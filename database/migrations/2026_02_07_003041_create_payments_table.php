<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('bill_id')->constrained('bills')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'e-wallet', 'cheque', 'other'])->nullable();
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->foreignId('validator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('validated_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('receipt_number')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['bill_id', 'status']);
            $table->index(['student_id', 'payment_date']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
