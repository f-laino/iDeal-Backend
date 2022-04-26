<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_offer', function (Blueprint $table) {
            $table->unsignedInteger('promotion_id')->index();
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
            $table->unsignedInteger('offer_id')->index();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['promotion_id', 'offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_offer', function (Blueprint $table) {
            $table->dropForeign('promotion_id_foreign');
            $table->dropForeign('offer_id_foreign');
        });
        Schema::dropIfExists('promotion_offer');
    }
}
