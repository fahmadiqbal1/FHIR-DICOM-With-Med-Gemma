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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('lab_revenue_percentage', 5, 2)->nullable()->comment('Doctor percentage from lab orders (0-100)');
            $table->decimal('radiology_revenue_percentage', 5, 2)->nullable()->comment('Doctor percentage from radiology orders (0-100)');
            $table->decimal('pharmacy_revenue_percentage', 5, 2)->nullable()->comment('Doctor percentage from prescriptions (0-100)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lab_revenue_percentage', 'radiology_revenue_percentage', 'pharmacy_revenue_percentage']);
        });
    }
};
