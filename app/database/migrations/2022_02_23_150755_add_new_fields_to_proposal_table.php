<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToProposalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
                $table->string('tutela_legale')
                    ->after('bollo_auto')
                    ->nullable();
                $table->string('assistenza_stradale')
                    ->after('bollo_auto')
                    ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn('tutela_legale');
            $table->dropColumn('assistenza_stradale');
        });
    }
}
