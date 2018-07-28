<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceOption extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_service_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable()->default(null);
            $table->integer('sort')->default(0);
            $table->integer('service_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            $table->float('min_order')->default(1);
            $table->float('order_multiples')->default(1);
            $table->boolean('enabled')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')->references('id')->on('production_services');
            $table->foreign('unit_id')->references('id')->on('core_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('production_service_options');
    }
}
