<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServicePrice extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_service_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_option_id')->unsigned();
            $table->integer('chanel_id')->nullable()->default(null)->unsigned()->comment('where chanel_id is null that mean is base price');
            $table->bigInteger('price');
            $table->boolean('current')->default(true);
            $table->integer('previous_id')->unsigned()->nullable()->default(null);
            $table->enum('change_type', ['up', 'down'])->nullable()->default(null);
            $table->bigInteger('difference');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_option_id')->references('id')->on('production_service_options');
            $table->foreign('previous_id')->references('id')->on('production_service_prices');
            $table->foreign('chanel_id')->references('id')->on('core_chanels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('production_service_prices');
    }
}
