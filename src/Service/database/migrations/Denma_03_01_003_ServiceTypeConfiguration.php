<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceTypeConfiguration extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_service_type_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('service_type_id')->unsigned();
            $table->integer('sequence');
            $table->enum('type', [
                'increasement',
                'multiplication',
                'multiplication_area',
                'multiplication_volume',
                'selection'
            ]);
            $table->longText('configuration');
            $table->boolean('required')->default(false);
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
        Schema::dropIfExists('production_service_type_configuration');
    }
}
