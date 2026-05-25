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
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained();
            $table->foreignUlid('category_id')->constrained();
            $table->enum('type',['income','expense']);
            $table->bigInteger('amount');
            $table->string('note')->nullable();
            $table->date('transaction_date');
            $table->string('receipt_path')->nullable();
            $table->foreignUlid('recurring_id')->nullable();
            //$table->foreignUlid('recurring_id')->nullable()->constrained('recurring_transactions')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
