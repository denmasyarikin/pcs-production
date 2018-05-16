<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceTypeOption extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_service_type_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('service_type_id')->unsigned();
            $table->enum('type', ['integer', 'array_of_integer', 'array']);
            $table->text('configuration')->comment('output should be numeric');
            $table->integer('default_value')->default(1)->comment('required if required');
            $table->boolean('required')->default(false);
            $table->boolean('affect_price')->default(false);
            $table->enum('affect_price_to', ['unit_price', 'unit_total'])->nullable()->default(null);
            $table->enum('affect_price_rule', ['fixed', 'percentage'])->nullable()->default(null);
            $table->enum('affect_price_opration', ['multiplication', 'division', 'addition', 'reduction'])->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('service_type_id')->references('id')->on('production_service_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('production_service_type_options');
    }
}
