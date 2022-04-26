<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_offer', function (Blueprint $table) {
            $table->unsignedInteger('agent_id')->index();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->unsignedInteger('offer_id')->index();
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['agent_id', 'offer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_offer', function (Blueprint $table) {
            $table->dropForeign('agent_id_foreign');
            $table->dropForeign('offer_id_foreign');
        });
        Schema::dropIfExists('agent_offer');
    }
}
