<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_connections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('driver', \App\CrmConnection::$DRIVERS);
            $table->string('uri');
            //I token vengono criptati prima di salvarli nel db
            $table->text('token');
            $table->string('owner');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_connections');
    }
}
