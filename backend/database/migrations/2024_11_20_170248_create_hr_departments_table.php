<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('hr_departments', function (Blueprint $table) {
            $table->string('dept_id')->primary();
            $table->string('dept_name')->unique();
            $table->string('dept_abbr')->unique();
            $table->string('dept_title')->nullable();
            $table->string('dpet_ptg_id')->default(0);
            $table->string('dept_str_id')->nullable();
            $table->integer('ud_sl_no')->nullable();
            $table->integer('clients_fg')->default(0);
            $table->integer('recshow_fg')->default(1);
            $table->string('org_id')->nullable();
            $table->integer('cbranch_id')->nullable();
            $table->integer('cobunit_id')->nullable();
            $table->integer('ptgunit_id')->nullable();
            $table->integer('clients_id')->nullable();
            $table->string('a_status_fg')->default('Yes');
            $table->integer('dept_fg')->default(1);
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
