<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add financial fields to users table for doctors
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('standard_fee', 10, 2)->nullable()->after('role');
            $table->integer('revenue_percentage')->default(70)->after('standard_fee'); // Doctor's percentage
            $table->boolean('is_active_doctor')->default(false)->after('revenue_percentage');
        });

        // Create doctor_earnings table for daily tracking
        Schema::create('doctor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('earning_date');
            $table->integer('patients_attended')->default(0);
            $table->decimal('total_consultations', 10, 2)->default(0);
            $table->decimal('doctor_share', 10, 2)->default(0);
            $table->decimal('admin_share', 10, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['doctor_id', 'earning_date']);
        });

        // Create daily_revenue_summary table for admin dashboard
        Schema::create('daily_revenue_summary', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date');
            $table->decimal('total_consultations', 10, 2)->default(0);
            $table->decimal('total_lab_fees', 10, 2)->default(0);
            $table->decimal('total_imaging_fees', 10, 2)->default(0);
            $table->decimal('total_other_fees', 10, 2)->default(0);
            $table->decimal('total_doctor_shares', 10, 2)->default(0);
            $table->decimal('total_admin_shares', 10, 2)->default(0);
            $table->decimal('gross_revenue', 10, 2)->default(0);
            $table->decimal('net_admin_revenue', 10, 2)->default(0);
            $table->integer('total_patients')->default(0);
            $table->integer('total_invoices')->default(0);
            $table->timestamps();
            
            $table->unique('summary_date');
        });

        // Add financial tracking to invoices table
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('lab_fees', 10, 2)->default(0)->after('amount');
            $table->decimal('imaging_fees', 10, 2)->default(0)->after('lab_fees');
            $table->decimal('other_fees', 10, 2)->default(0)->after('imaging_fees');
            $table->decimal('doctor_share', 10, 2)->default(0)->after('other_fees');
            $table->decimal('admin_share', 10, 2)->default(0)->after('doctor_share');
            $table->integer('doctor_percentage')->default(70)->after('admin_share');
        });

        // Create expense_categories table
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create daily_expenses table
        Schema::create('daily_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['lab_fees', 'imaging_fees', 'other_fees', 'doctor_share', 'admin_share', 'doctor_percentage']);
        });
        
        Schema::dropIfExists('daily_expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('daily_revenue_summary');
        Schema::dropIfExists('doctor_earnings');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['standard_fee', 'revenue_percentage', 'is_active_doctor']);
        });
    }
};
