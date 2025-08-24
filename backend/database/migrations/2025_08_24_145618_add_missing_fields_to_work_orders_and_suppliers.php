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
        Schema::table('work_orders', function (Blueprint $table) {
            // Add missing fields for work orders
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            
            // Drop and recreate total_amount as it conflicts with estimated_cost/actual_cost
            $table->dropColumn('total_amount');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            // Add missing fields for suppliers
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('active');
            $table->string('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn(['assigned_to', 'estimated_cost', 'actual_cost', 'location', 'category']);
            $table->decimal('total_amount', 10, 2)->nullable();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['status', 'category']);
        });
    }
};
