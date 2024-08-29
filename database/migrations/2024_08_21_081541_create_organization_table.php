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
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('org_id')->primary();
            $table->string('org_name');
            $table->string('org_admin_email')->unique();
            $table->string('org_domain_name')->unique();
            $table->text('temporary_password')->nullable();
            $table->timestamp('password_expires_at')->nullable();
            $table->integer('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamps();

            $table->unique(['org_admin_email', 'org_domain_name', 'otp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization');
    }
};
