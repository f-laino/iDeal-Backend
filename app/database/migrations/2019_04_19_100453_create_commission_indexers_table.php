<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionIndexersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_indexers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('broker');
            $table->jsonb('pattern');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['broker', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission_indexers');
    }
}
