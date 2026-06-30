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
        Schema::create('settlement_requests', function (Blueprint $table) {
            $table->ulid('id');
            $table->foreignUlid('expense_split_id')->constrained('expense_splits')->cascadeOnDelete();
            $table->foreignUlid('claimed_by')->constrained('users');
            $table->bigInteger('amount');
            $table->enum('status',['pending','confirmed','rejected'])->default('pending');
            $table->foreignUlid('confirmed_by')->nullable()->constrained('users');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlement_requests');
    }
};
