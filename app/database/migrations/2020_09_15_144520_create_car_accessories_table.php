<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\CarAccessory;

class CreateCarAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_accessories', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', CarAccessory::$ALLOWED_TYPES)
                ->default(CarAccessory::$DEFAULT_TYPE)
                ->comment('Indica la tipologia di accessorio');
            $table->integer('car_accessory_groups_id')->unsigned();
            $table->integer('car_id')->unsigned();

            $table->string('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('standard_description')->nullable();
            $table->float('price')
                ->default(0)
                ->comment("Indica il prezzo di mercato dell'accessorio");
            $table->boolean('available')
                ->default(TRUE)
                ->comment("Indica se l'accessorio è configurabile sulla vettura o meno. Questa avviene in quanto ci sono delle discrepanzee fra il nostro fornitore dati e quello dei broker di noleggio");
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('car_accessory_groups_id')->references('id')->on('car_accessory_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_accessories', function (Blueprint $table) {
            $table->dropForeign('car_id_foreign');
            $table->dropForeign('car_accessory_groups_id_foreign');
        });
        Schema::dropIfExists('car_accessories');
    }
}
