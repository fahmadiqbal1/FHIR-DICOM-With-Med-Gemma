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
            $table->string('phone')->nullable()->after('email');
            $table->string('department')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('department');
            $table->string('profile_picture')->nullable()->after('bio');
            $table->boolean('email_notifications')->default(true)->after('profile_picture');
            $table->boolean('system_alerts')->default(true)->after('email_notifications');
            $table->boolean('audit_alerts')->default(true)->after('system_alerts');
            $table->boolean('two_factor_enabled')->default(false)->after('audit_alerts');
            $table->integer('session_timeout')->default(120)->after('two_factor_enabled');
            $table->timestamp('last_login_at')->nullable()->after('session_timeout');
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
                'last_login_at'
            ]);
        });
    }
};
