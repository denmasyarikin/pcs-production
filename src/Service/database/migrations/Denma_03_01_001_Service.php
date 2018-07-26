<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Service extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('production_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('sort')->default(0);
            $table->text('description')->nullable()->default(null);
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('production_services');
    }
}
