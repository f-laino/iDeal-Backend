<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQualifiedToQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('last_qualified_step')->after('status')->nullable()->comment('Indica il blocco dal quale il chatbot deve ripartire');
            $table->boolean('qualified')->after('last_qualified_step')->default(FALSE)->comment('Indica se e stato qualificato usando un flusso di qualifica');
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
            $table->removeColumn('last_qualified_step');
            $table->removeColumn('qualified');
        });
    }
}
