<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('hr_designations', function (Blueprint $table) {
            $table->bigInteger('desig_id')->primary();
            $table->string('desig_name')->unique();
            $table->string('desig_abbr')->unique();
            $table->string('desig_title')->nullable();
            $table->integer('desig_ptg_id')->nullable();
            $table->bigInteger('desig_str_id')->nullable();
            $table->integer('ud_sl_no')->nullable();
            $table->string('org_id')->nullable();
            $table->string('dept_id')->nullable();
            $table->integer('clients_fg')->default(0);
            $table->integer('recshow_fg')->default(1);
            $table->integer('cbranch_id')->nullable();
            $table->integer('cobunit_id')->nullable();
            $table->integer('ptgunit_id')->nullable();
            $table->integer('clients_id')->default(0);
            $table->integer('desig_fg')->default(1);
            $table->integer('default_fg')->default(0);
            $table->string('a_status_fg')->default('Yes');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
