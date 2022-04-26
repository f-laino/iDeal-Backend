<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCommissionIndexerFromGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign(['commission_indexer_id']);
            $table->dropColumn('commission_indexer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->unsignedInteger('commission_indexer_id')->nullable()->after('group_leader');
            $table->foreign('commission_indexer_id')->references('id')->on('commission_indexers');
        });
    }
}
