<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('imaging_study_id')->nullable()->constrained('imaging_studies')->nullOnDelete();
            $table->string('code')->nullable();
            $table->string('display')->nullable();
            $table->date('onset_date')->nullable();
            $table->string('clinical_status')->nullable();
            $table->string('verification_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};
