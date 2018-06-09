<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceOptionConfiguration extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_service_option_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('sequence')->default(1);
            $table->integer('service_option_id')->unsigned();
            $table->string('type');
            $table->longText('structure');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_option_id', 'serv_opt')->references('id')->on('production_service_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('production_service_option_configurations');
    }
}
