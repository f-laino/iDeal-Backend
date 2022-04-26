<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('included')
                    ->after('icon')
                    ->default(false);
            $table->unsignedSmallInteger('price')
                    ->after('included')
                    ->nullable();

            $table->dropUnique('services_slug_unique');
            $table->unique(['slug', 'included', 'price'], 'slug_included_price_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('included');
            $table->dropColumn('price');

            $table->unique('slug', 'services_slug_unique');
            $table->dropUnique(['slug', 'included', 'price'], 'slug_included_price_unique');
        });
    }
}
