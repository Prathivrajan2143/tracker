<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsLoginPageTable extends Migration
{
    public function up()
    {
        Schema::create('organizations_login_page', function (Blueprint $table) {
            $table->uuid('org_id');
            $table->string('logo');
            $table->text('welcomeQuote');
            $table->foreign('org_id')->references('org_id')->on('organizations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizations_login_page');
    }
}

