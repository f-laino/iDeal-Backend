<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('offer_id')->unsigned();
            $table->integer('agent_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->decimal('deposit', 8, 2);
            $table->decimal('monthly_rate', 8, 2);
            $table->tinyInteger('duration');
            $table->integer('distance');
            $table->string('franchise_insurance')->nullable();
            $table->string('franchise_kasko')->nullable();
            $table->boolean('change_tires')->default(false);
            $table->boolean('car_replacement')->default(false);
            $table->jsonb('car_accessories')->nullable()
                    ->comment('Elenco degli optional richiesti dal cliente');
            $table->boolean('bollo_auto')->default(false);
            $table->integer('print_count')->default(0);
            $table->dateTime('last_print_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('offer_id')
                    ->references('id')
                    ->on('offers')
                    ->onDelete('cascade');
            $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
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
        Schema::dropIfExists('proposals');
    }
}
