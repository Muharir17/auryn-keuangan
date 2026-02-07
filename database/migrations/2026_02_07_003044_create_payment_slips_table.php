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
        Schema::create('payment_slips', function (Blueprint $table) {
            $table->id();
            $table->string('slip_number')->unique();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('cascade');
            $table->foreignId('bill_id')->constrained('bills')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_type');
            $table->integer('file_size');
            $table->string('file_hash')->nullable();
            $table->enum('status', ['uploaded', 'processing', 'processed', 'rejected'])->default('uploaded');
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['bill_id', 'status']);
            $table->index(['student_id', 'created_at']);
            $table->index(['uploaded_by', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_slips');
    }
};
