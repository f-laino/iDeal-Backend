<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string("code", 250);
            $table->unsignedInteger("car_id");
            $table->decimal("monthly_rate",8 ,2);
            $table->decimal("deposit", 8, 2);
            $table->integer("distance");
            $table->tinyInteger("duration");

            $table->string("broker");

            $table->unsignedInteger("crm_id");
            $table->boolean("status");
            $table->boolean('highlighted')->default(FALSE);
            $table->timestamps();
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
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('carmodel_id_foreign');
        });
        Schema::dropIfExists('offers');
    }
}
