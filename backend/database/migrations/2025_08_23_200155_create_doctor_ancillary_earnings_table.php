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
        Schema::create('doctor_ancillary_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('service_type')->comment('lab, radiology, or pharmacy'); 
            $table->string('service_id')->comment('ID of the service/order/prescription');
            $table->decimal('service_amount', 10, 2)->comment('Total amount of the service');
            $table->decimal('doctor_percentage', 5, 2)->comment('Doctor percentage at time of service');
            $table->decimal('doctor_earning', 10, 2)->comment('Calculated doctor earning');
            $table->string('patient_name')->nullable();
            $table->date('service_date');
            $table->json('metadata')->nullable()->comment('Additional service details');
            $table->timestamps();
            
            $table->index(['doctor_id', 'service_type']);
            $table->index(['service_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_ancillary_earnings');
    }
};
