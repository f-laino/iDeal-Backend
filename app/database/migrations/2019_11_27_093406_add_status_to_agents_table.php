<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->enum('status', ['ACTIVATED', 'SUSPENDED', 'DISABLED', 'PENDING_ACTIVATION'])->default('PENDING_ACTIVATION')->comment(
                "Campo da utilizzare per indicare lo stato dell'account agente"
            )->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->removeColumn('status');
        });
    }
}
