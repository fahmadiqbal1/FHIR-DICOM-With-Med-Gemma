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
        Schema::table('imaging_studies', function (Blueprint $table) {
            $table->datetime('study_date')->nullable()->after('description');
            $table->enum('urgency', ['low', 'normal', 'high'])->default('normal')->after('status');
            $table->json('file_paths')->nullable()->after('urgency');
            $table->unsignedBigInteger('created_by')->nullable()->after('file_paths');
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('imaging_studies', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['study_date', 'urgency', 'file_paths', 'created_by']);
        });
    }
};
