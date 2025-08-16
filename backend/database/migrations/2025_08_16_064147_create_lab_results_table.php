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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained('lab_orders')->onDelete('cascade');
            $table->foreignId('equipment_id')->nullable()->constrained('lab_equipment')->onDelete('set null');
            $table->string('test_code');
            $table->string('test_name');
            $table->string('result_value');
            $table->string('result_units')->nullable();
            $table->string('reference_range')->nullable();
            $table->enum('result_flag', ['normal', 'high', 'low', 'critical', 'abnormal', 'unknown'])->default('unknown');
            $table->enum('result_status', ['preliminary', 'final', 'corrected', 'needs_verification'])->default('preliminary');
            $table->timestamp('performed_at');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('source_type', ['equipment', 'ocr', 'manual'])->default('equipment');
            $table->json('raw_data')->nullable(); // Original equipment data
            $table->string('ocr_image_path')->nullable(); // Path to OCR image if used
            $table->decimal('ocr_confidence', 3, 2)->nullable(); // OCR confidence score (0.00-1.00)
            $table->boolean('quality_control_passed')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['lab_order_id', 'test_code']);
            $table->index(['equipment_id', 'performed_at']);
            $table->index(['source_type', 'result_status']);
            $table->index('performed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
