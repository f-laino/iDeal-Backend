<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProposalToQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->bigInteger('proposal_id')
                    ->unsigned()
                    ->nullable()
                    ->after('id');

            $table->foreign('proposal_id')
                    ->references('id')
                    ->on('proposals')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign('quotations_proposal_id_foreign');
            $table->dropColumn('proposal_id');
        });
    }
}
