<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('zip_code', 10);
            $table->string('fiscal_code', 16);
            $table->string('iban', 34)->nullable();
            $table->unsignedInteger('contractual_category_id');
            $table->unsignedInteger('crm_id')->nullable();
            $table->string('vat_number', 20)->nullable();
            $table->string('business_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('marketing')->nullable();
            $table->timestamp('third_party')->nullable();
            $table->timestamps();

            $table->foreign('contractual_category_id')->references('id')->on('contractual_categories');

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
            $table->dropForeign('contractual_category_id_foreign');
        });
        Schema::dropIfExists('customers');
    }
}
