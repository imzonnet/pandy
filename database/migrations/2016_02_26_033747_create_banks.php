<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('interest_rate', 12);
            $table->decimal('comparison_rate', 12);
            $table->decimal('annual_fees', 12);
            $table->decimal('monthly_fees', 12);
            $table->string('special');
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
        Schema::drop('banks');
    }
}
