<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceType extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_service_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('service_id')->unsigned();
            $table->integer('unit_id')->unsigned();
            // dimension
            $table->boolean('depending_to_dimension')->default(false);
            $table->enum('dimension', ['length', 'area', 'volume', 'weight'])->nullable()->default(null);
            $table->integer('dimension_unit_id')->unsigned()->nullable()->default(null);
            $table->float('length')->nullable()->default(null);
            $table->float('width')->nullable()->default(null);
            $table->float('height')->nullable()->default(null);
            $table->float('weight')->nullable()->default(null);
            // decreasement
            $table->boolean('price_decreasement')->default(false);
            $table->float('price_decrease_multiples')->nullable()->default(null);
            $table->float('price_decrease_percentage')->nullable()->default(null);

            $table->boolean('enabled')->default(false);
            $table->float('min_order')->default(1);
            $table->float('order_multiples')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_id')->references('id')->on('production_services');
            $table->foreign('unit_id')->references('id')->on('core_units');
            $table->foreign('dimension_unit_id')->references('id')->on('core_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('production_service_types');
    }
}
