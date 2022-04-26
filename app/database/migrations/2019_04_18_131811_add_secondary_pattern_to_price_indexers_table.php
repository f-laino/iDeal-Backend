<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryPatternToPriceIndexersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_indexers', function (Blueprint $table) {
            $table->jsonb('secondary_pattern')->after('pattern');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_indexers', function (Blueprint $table) {
            $table->dropColumn('secondary_pattern');
        });
    }
}
