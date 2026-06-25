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
        Schema::create('expense_splits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('group_expense_id')->constrained('group_expenses')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users');
            $table->bigInteger('amount_owed');
            $table->bigInteger('amount_paid')->default(0);
            $table->boolean('is_settled')->default(false);
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();

            $table->unique(['group_expense_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_splits');
    }
};
