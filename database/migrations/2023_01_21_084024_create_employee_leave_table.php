<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave', function (Blueprint $table) {
            $table->id();
            $table->string('ref_number')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('state_reason')->nullable();
            $table->date('date_from')->nullable();
            $table->integer('ext_from')->nullable();
            $table->integer('ext_to')->nullable();
            $table->date('date_to')->nullable();
            $table->double('leave')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->default(0);
            $table->integer('deleted')->default(0);
            $table->integer('approver')->nullable();
            $table->text('feedback')->nullable();
            $table->date('approve_date')->nullable();
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
        Schema::dropIfExists('employee_leave');
    }
}
