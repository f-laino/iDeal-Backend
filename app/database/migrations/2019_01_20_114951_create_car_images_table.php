<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_id')->unsigned();
            $table->string("code");
            $table->string('path',250);
            $table->string('image_alt')->nullable();
            $table->enum('type', \App\Image::getPositions())->default(\App\Image::$_DEFAULT_POSITION);
            $table->softDeletes();
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_images', function (Blueprint $table) {
            $table->dropForeign('car_id_foreign');
        });
        Schema::dropIfExists('car_images');
    }
}
