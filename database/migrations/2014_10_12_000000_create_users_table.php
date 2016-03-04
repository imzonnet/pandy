<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->integer('group')->default(0);
            $table->string('phone');
            $table->decimal('loan_amount', 12);
            $table->decimal('interest_rate', 12);
            $table->decimal('switching_costs', 12);
            $table->decimal('ongoing_costs', 12);
            $table->integer('loan_term');
            $table->string('api_token', 60);
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
