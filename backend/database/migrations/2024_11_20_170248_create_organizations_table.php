<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('hr_organizations', function (Blueprint $table) {
            $table->string('org_id')->primary();
            $table->string('org_name')->unique();
            $table->string('org_abbr')->unique();
            $table->string('org_slogan')->nullable();
            $table->string('org_details')->nullable();
            $table->string('org_email')->nullable();
            $table->string('org_phone')->nullable();
            $table->string('org_website')->nullable();
            $table->string('org_address')->nullable();
            $table->string('org_logo')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hr_organizations');
    }
}
