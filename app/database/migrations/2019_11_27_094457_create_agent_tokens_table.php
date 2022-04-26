<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_tokens', function (Blueprint $table) {
            $table->unsignedInteger('agent_id')->index();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->string('token');
            $table->timestamps();

            $table->unique(['token']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('agent_tokens', function (Blueprint $table) {
            $table->dropForeign('agent_id_foreign');
        });
        Schema::dropIfExists('agent_tokens');
    }
}
