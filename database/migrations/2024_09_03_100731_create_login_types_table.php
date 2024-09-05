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
        Schema::create('login_types', function (Blueprint $table) {
            $table->id();
            
            // Users Foreign Key Constraints
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Foreign key to organizations table

            //  Organizations Foreign Key Constraints
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->onDelete('cascade');
                
            $table->string('domain_name'); // Domain name column
            $table->string('login_type')->nullable(); // Login type name column
            $table->string('sso_provider')->nullable(); // Login type name column
        
            // Unique constraint for the combination of user_id, organization_id, and domain_name
            $table->unique(['domain_name', 'login_type']);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_types');
    }
};
