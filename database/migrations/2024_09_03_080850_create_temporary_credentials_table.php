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
        Schema::create('temporary_credentials', function (Blueprint $table) {
            $table->id();

            // Users Foreign Key Constraints
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade'); // Foreign key to organizations table

            //  Organizations Foreign Key Constraints
            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->onDelete('cascade');
                
            $table->string('temporary_password', 500)->nullable();
            $table->timestamp('password_expires_at')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_credentials');
    }
};
