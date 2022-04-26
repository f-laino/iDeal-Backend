<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stage')->default(1);
            $table->unsignedInteger('offer_id');
            $table->unsignedInteger('agent_id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('crm_id')->nullable()->comment('Pipedrive Deal id');

            $table->decimal("deposit", 8, 2);
            $table->decimal("monthly_rate",8 ,2);
            $table->tinyInteger("duration");
            $table->integer("distance");

            $table->string('franchise_insurance')->nullable();
            $table->string('franchise_kasko')->nullable();
            $table->boolean('upload_documents')->default(FALSE);
            $table->boolean('change_tires')->default(FALSE);

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
