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
        Schema::table('invoices', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('invoices', 'owner_share')) {
                $table->decimal('owner_share', 10, 2)->nullable()->after('doctor_share');
            }
            if (!Schema::hasColumn('invoices', 'issuer_role')) {
                $table->enum('issuer_role', ['admin', 'lab_tech', 'radiologist', 'pharmacist'])->nullable()->after('doctor_percentage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['owner_share', 'issuer_role']);
        });
    }
};
