<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_service', function (Blueprint $table) {
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')
                ->on('services')->onDelete('cascade');

            $table->integer('offer_id')->unsigned()->nullable();
            $table->foreign('offer_id')->references('id')
                ->on('offers')->onDelete('cascade');

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
        Schema::table('offer_service', function (Blueprint $table) {
            $table->dropForeign('service_id_foreign');
            $table->dropForeign('offer_id_foreign');
        });
        Schema::dropIfExists('offer_service');
    }
}
