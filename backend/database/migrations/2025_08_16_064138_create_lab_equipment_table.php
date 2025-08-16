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
        Schema::create('lab_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model');
            $table->string('manufacturer');
            $table->string('serial_number')->nullable();
            $table->string('ip_address')->nullable();
            $table->integer('port')->nullable();
            $table->enum('connection_type', ['serial', 'tcp', 'file_transfer', 'hl7'])->default('tcp');
            $table->enum('protocol', ['astm', 'hl7', 'lis', 'custom'])->default('astm');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_connected_at')->nullable();
            $table->json('configuration')->nullable(); // Equipment-specific settings
            $table->json('supported_tests')->nullable(); // Array of test codes
            $table->boolean('auto_fetch_enabled')->default(true);
            $table->enum('backup_method', ['ocr', 'manual', 'none'])->default('ocr');
            $table->timestamps();
            
            $table->index(['is_active', 'auto_fetch_enabled']);
            $table->index('last_connected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_equipment');
    }
};
