<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if invoices table exists and add missing columns
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'doctor_id')) {
                    $table->foreignId('doctor_id')->nullable()->constrained('users')->after('patient_id');
                }
                if (!Schema::hasColumn('invoices', 'service_type')) {
                    $table->string('service_type')->default('consultation')->after('doctor_id');
                }
                if (!Schema::hasColumn('invoices', 'description')) {
                    $table->text('description')->nullable()->after('amount');
                }
            });
        } else {
            // Create invoices table if it doesn't exist
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('patient_id')->constrained()->onDelete('cascade');
                $table->foreignId('doctor_id')->nullable()->constrained('users');
                $table->string('service_type')->default('consultation');
                $table->decimal('amount', 10, 2);
                $table->string('status')->default('pending');
                $table->text('description')->nullable();
                $table->string('email_sent_to')->nullable();
                $table->timestamp('email_sent_at')->nullable();
                $table->timestamp('due_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
