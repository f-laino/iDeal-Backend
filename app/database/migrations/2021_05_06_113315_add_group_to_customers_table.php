<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('group_id')
                    ->unsigned()
                    ->nullable()
                    ->after('email');
            $table->string('phone')->nullable()->change();
            $table->string('zip_code')->nullable()->change();

            $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_group_id_foreign');
            $table->dropColumn('group_id');
            $table->string('phone')->nullable(false)->change();
            $table->string('zip_code')->nullable(false)->change();
        });
    }
}
