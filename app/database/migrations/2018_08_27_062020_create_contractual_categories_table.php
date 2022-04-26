<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractualcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('contractual_categories', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code');
                $table->string('description');
                $table->boolean('for_private')->default(FALSE);
                $table->boolean( 'for_business')->default(FALSE);
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contractual_categories');
    }
}
