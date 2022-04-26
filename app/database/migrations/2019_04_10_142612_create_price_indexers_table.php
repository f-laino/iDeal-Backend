<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceIndexersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_indexers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('broker');
            $table->string('segment');
            $table->jsonb('pattern');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['broker', 'segment', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_indexers');
    }
}
