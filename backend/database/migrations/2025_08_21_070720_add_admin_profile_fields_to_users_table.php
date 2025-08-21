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
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'profile_picture')) {
                $table->string('profile_picture')->nullable()->after('bio');
            }
            
            // Notification preferences
            if (!Schema::hasColumn('users', 'email_notifications')) {
                $table->boolean('email_notifications')->default(true)->after('profile_picture');
            }
            if (!Schema::hasColumn('users', 'system_alerts')) {
                $table->boolean('system_alerts')->default(true)->after('email_notifications');
            }
            if (!Schema::hasColumn('users', 'audit_alerts')) {
                $table->boolean('audit_alerts')->default(true)->after('system_alerts');
            }
            
            // Security settings
            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('audit_alerts');
            }
            if (!Schema::hasColumn('users', 'session_timeout')) {
                $table->integer('session_timeout')->default(120)->after('two_factor_enabled'); // minutes
            }
            
            // Activity tracking
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('session_timeout');
            }
            
            // Revenue share for doctors
            if (!Schema::hasColumn('users', 'revenue_share')) {
                $table->decimal('revenue_share', 5, 2)->nullable()->after('last_login_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'department', 
                'bio',
                'profile_picture',
                'email_notifications',
                'system_alerts',
                'audit_alerts',
                'two_factor_enabled',
                'session_timeout',
                'last_login_at',
                'revenue_share'
            ]);
        });
    }
};
