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
        Schema::create('imaging_test_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('modality'); // X-ray, CT, MRI, Ultrasound, etc.
            $table->text('description')->nullable();
            $table->string('body_part')->nullable();
            $table->json('preparation_instructions')->nullable(); // JSON array of instructions
            $table->decimal('estimated_duration', 4, 1)->nullable(); // in minutes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_test_types');
    }
};
