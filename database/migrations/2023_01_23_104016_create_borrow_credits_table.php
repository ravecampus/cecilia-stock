<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrow_credits', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('employee_leave_id')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->integer('status')->default(0);
            $table->integer('deleted')->default(0);
            $table->double('credits')->nullable();
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
        Schema::dropIfExists('borrow_credits');
    }
}
