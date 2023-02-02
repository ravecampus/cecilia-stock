<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('leave_type_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('employee_leave_id')->nullable();
            $table->integer('notify')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emp_notifications');
    }
}
