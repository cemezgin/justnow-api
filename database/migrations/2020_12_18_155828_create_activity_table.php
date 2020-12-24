<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('duration');
            $table->float('lat')->nullable();
            $table->float('long')->nullable();
            $table->string('location');
            $table->string('country');
            $table->float('buy_price');
            $table->string('currency');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('image');
            $table->string('operational_days');
            $table->float('rate');
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
        Schema::dropIfExists('activity');
    }
}
