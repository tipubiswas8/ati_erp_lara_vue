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
        Schema::create('sa_permission_sa_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sa_permission_id')->constrained('sa_permissions')->onDelete('cascade');
            $table->foreignId('sa_role_id')->constrained('sa_roles')->onDelete('cascade');
            $table->string('org_id')->nullable();
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
        Schema::dropIfExists('sa_permission_sa_role');
    }
};
