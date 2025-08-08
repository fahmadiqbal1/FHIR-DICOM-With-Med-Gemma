<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Imaging studies
        Schema::create('imaging_studies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('accession_number')->nullable()->index();
            $table->string('study_instance_uid')->unique();
            $table->string('description')->nullable();
            $table->string('modality')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->string('status')->default('registered'); // registered | available | cancelled | entered-in-error | unknown
            $table->timestamps();
        });

        Schema::create('dicom_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imaging_study_id')->constrained('imaging_studies')->cascadeOnDelete();
            $table->string('series_instance_uid')->index();
            $table->string('sop_instance_uid')->unique();
            $table->string('file_path'); // encrypted storage path
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('checksum')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('frames')->nullable();
            $table->string('content_type')->nullable();
            $table->timestamps();
        });

        // AI results for MedGemma
        Schema::create('ai_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imaging_study_id')->constrained('imaging_studies')->cascadeOnDelete();
            $table->string('model')->default('medgemma');
            $table->string('request_id')->nullable()->index();
            $table->string('status')->default('queued'); // queued|processing|completed|failed
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->json('result')->nullable();
            $table->timestamps();
        });

        // Audit logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->morphs('subject');
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
            $table->index(['action', 'created_at']);
        });

        // Pharmacy
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('ndc_code')->nullable()->index();
            $table->string('name');
            $table->string('form')->nullable();
            $table->string('strength')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('controlled_substance_schedule')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete();
            $table->string('lot_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('location')->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('status')->default('available'); // available|reserved|expired|damaged
            $table->timestamps();
            $table->index(['expiry_date', 'status']);
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('medication_id')->constrained('medications')->cascadeOnDelete();
            $table->foreignId('prescribed_by')->constrained('users')->cascadeOnDelete();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->string('route')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('refills_allowed')->default(0);
            $table->integer('refills_used')->default(0);
            $table->string('status')->default('pending'); // pending|filled|cancelled|expired
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Laboratory
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('normal_range_low', 10, 2)->nullable();
            $table->decimal('normal_range_high', 10, 2)->nullable();
            $table->string('units')->nullable();
            $table->string('specimen_type')->nullable();
            $table->timestamps();
        });

        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->foreignId('ordered_by')->constrained('users')->cascadeOnDelete();
            $table->string('priority')->default('routine'); // routine|urgent|stat
            $table->string('status')->default('ordered'); // ordered|collected|processing|resulted|cancelled
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('resulted_at')->nullable();
            $table->string('result_value')->nullable();
            $table->string('result_flag')->nullable(); // normal|high|low|critical
            $table->text('result_notes')->nullable();
            $table->timestamps();
            $table->index(['status', 'priority']);
        });

        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes')->default(30);
            $table->string('status')->default('scheduled'); // scheduled|checked-in|completed|cancelled|no-show
            $table->string('location')->nullable();
            $table->string('telemedicine_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Clinical Notes
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('users')->cascadeOnDelete();
            $table->longText('soap_subjective')->nullable();
            $table->longText('soap_objective')->nullable();
            $table->longText('soap_assessment')->nullable();
            $table->longText('soap_plan')->nullable();
            $table->string('icd10_code')->nullable();
            $table->string('cpt_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_notes');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('lab_orders');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('medications');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('ai_results');
        Schema::dropIfExists('dicom_images');
        Schema::dropIfExists('imaging_studies');
    }
};
