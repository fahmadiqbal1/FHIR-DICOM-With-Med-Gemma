<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create owner role if it doesn't exist
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        
        // Create owner user if it doesn't exist
        $ownerUser = User::where('email', 'owner@medgemma.com')->first();
        
        if (!$ownerUser) {
            $ownerUser = User::create([
                'name' => 'Business Owner',
                'email' => 'owner@medgemma.com',
                'password' => Hash::make('owner123'),
                'email_verified_at' => now(),
            ]);
            
            echo "Owner user created: owner@medgemma.com\n";
        } else {
            echo "Owner user already exists\n";
        }
        
        // Assign owner role
        if (!$ownerUser->hasRole('owner')) {
            $ownerUser->assignRole('owner');
            echo "Owner role assigned\n";
        } else {
            echo "Owner already has owner role\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove owner user
        User::where('email', 'owner@medgemma.com')->delete();
        
        // Remove owner role
        Role::where('name', 'owner')->delete();
    }
};
