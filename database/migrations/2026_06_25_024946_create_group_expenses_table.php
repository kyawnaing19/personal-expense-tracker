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
        Schema::create('group_expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('group_id')->constrained('groups')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users');
            $table->foreignUlid('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->bigInteger('amount');
            $table->string('description')->nullable();
            $table->date('expense_date');
            $table->enum('split_type', ['equally', 'custom']);
            $table->boolean('include_payer')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_expenses');
    }
};
