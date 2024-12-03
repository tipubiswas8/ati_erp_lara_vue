<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// php artisan migrate --path=/database/migrations/my_sql/
// php artisan migrate --path=/database/migrations/my_sql/2024_10_23_094229_create_hr_employees_table.php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_employees', function (Blueprint $table) {
            // $table->id('employee_id');
            $table->unsignedBigInteger('employee_id')->primary();
            $table->string('efull_name', 50);
            $table->string('deprtmn_id', 20);
            $table->integer('desgton_id');
            $table->string('usrdemp_id', 20);
            $table->integer('astatus_fg');
            $table->integer('userdsl_no');
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('updated_by')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->integer('deleted_by')->nullable();
            $table->date('deleted_at')->nullable();
            $table->integer('relgion_id')->nullable();
            $table->string('biometicid', 20)->nullable();
            $table->date('emphire_dt')->nullable();
            $table->date('empstrt_dt')->nullable();
            $table->string('bld_grp_id', 5)->nullable();
            $table->string('sorgndr_id', 5)->nullable();
            $table->string('ofie_email', 100)->nullable();
            $table->string('ppo_hemail', 100)->nullable();
            $table->string('omobile_no', 15)->nullable();
            $table->string('pmobile_no', 15)->nullable();
            $table->integer('mstatus_id')->nullable();
            $table->date('dtof_birth')->nullable();
            $table->string('plof_birth', 100)->nullable();
            $table->string('nationalid', 20)->nullable();
            $table->integer('weight_kgs')->nullable();
            $table->integer('height_cms')->nullable();
            $table->string('color_eyes', 100)->nullable();
            $table->string('idnty_mark', 120)->nullable();
            $table->string('pres_addrs', 300)->nullable();
            $table->string('perm_addrs', 300)->nullable();
            $table->integer('emp_id')->nullable();
            $table->string('empl_photo', 200)->nullable();
            $table->integer('empshow_fg')->default(1)->nullable();
            $table->string('org_id')->nullable();
            $table->integer('cbranch_id')->default(0)->nullable();
            $table->integer('cobunit_id')->default(0)->nullable();
            $table->integer('ptgunit_id')->default(0)->nullable();
            $table->integer('recshow_fg')->default(0)->nullable();
            $table->string('emp_temp_to', 300)->nullable();
            $table->string('emp_temp_cc', 1200)->nullable();
            $table->integer('rperson_id')->nullable();
            $table->string('designation', 500)->nullable();
            $table->string('emer_addrs', 300)->nullable();
            $table->string('emp_sigimg', 200)->nullable();
            $table->string('emp_nidimg', 200)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('ppo_haemal', 100)->nullable();
            $table->string('p2ndmob_no', 15)->nullable();
            $table->string('p3rdmob_no', 15)->nullable();
            $table->string('emp_pp_img', 200)->nullable();
            $table->string('emp_bc_img', 200)->nullable();
            $table->string('pwsadrlink', 300)->nullable();
            $table->string('pskypeaddr', 100)->nullable();
            $table->date('temn_dt')->nullable();
            $table->string('temn_by', 20)->nullable();
            $table->string('temn_remarks', 200)->nullable();
            $table->string('temn_reason', 200)->nullable();
            $table->integer('ptg_emp_id')->nullable();
            $table->integer('emp_typ_id', false, true)->nullable();
            $table->string('fcm_reg_id', 999)->nullable();
            $table->integer('emp_cat_id')->default(1)->nullable();
            $table->integer('wkshift_id', false, true)->nullable();
            $table->string('emk_person', 6)->nullable();
            $table->integer('schedule')->default(0)->nullable();
            $table->integer('empltyp_id')->nullable();
            $table->integer('empltp_dur')->nullable();
            $table->string('old_emp_id', 16)->nullable();
            $table->integer('hireemp_id', false, true)->nullable();
            $table->string('emp_bn_name', 300)->nullable();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_employees');
    }
};
