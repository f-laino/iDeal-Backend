<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('fuel_id')->unsigned();

            $table->string('codice_motornet', 150);
            $table->string('codice_eurotax', 150);

            $table->string("codice_gruppo_storico", 150);
            $table->string("descrizione_gruppo_storico", 250);

            $table->string("codice_serie_gamma", 150);
            $table->string( "descrizione_serie_gamma", 250);

            $table->string("codice_modello", 150);
            $table->string("descrizione_modello", 250);

            $table->string("cod_gamma_mod", 150);
            $table->string("modello", 250);
            $table->string("allestimento", 250);

            $table->string("cilindrata");
            $table->string("cavalli_fiscali");

            $table->string("tipo_motore", 250);
            $table->string("desc_motore", 250)->nullable();
            $table->string("hp");
            $table->string("kw");
            $table->string("euro");
            $table->string( "emissioni_co2", 10);
            $table->string("consumo_medio",10);
            $table->string("alimentazione");

            $table->string("codice_cambio",100);
            $table->string("nome_cambio", 250);
            $table->string("descrizione_cambio", 250);

            $table->string("descrizione_marce")->nullable();
            $table->string("accelerazione",10)->nullable();
            $table->string("altezza", 10)->nullable();

            $table->string("cilindri")->nullable();
            $table->string("consumo_urbano", 10);
            $table->string("consumo_extra_urbano", 10);
            $table->string("coppia")->nullable();
            $table->string("numero_giri")->nullable();
            $table->string("larghezza");
            $table->string("lunghezza");
            $table->string("pneumatici_anteriori")->nullable();
            $table->string("pneumatici_posteriori")->nullable();
            $table->string("valvole")->nullable();
            $table->string("velocita")->nullable();
            $table->string("porte")->nullable();

            $table->string("posti")->nullable();
            $table->string("descrizione_trazione")->nullable();
       //     $table->string("altezza_minima")->nullable();
            $table->string("autonomia_media")->nullable();
            $table->string("autonomia_max")->nullable();
            $table->string("bagagliaio");
            $table->string("cavalli_ibrido")->nullable();
            $table->string("cavalli_totale")->nullable();
            $table->string("potenza_ibrido")->nullable();
            $table->string("potenza_totale")->nullable();
            $table->string("coppia_ibrido")->nullable();
            $table->string("coppia_totale")->nullable();
            $table->boolean("neo_patentati")->default(FALSE);
            $table->string("numero_giri_ibrido")->nullable();
            $table->string("numero_giri_totale")->nullable();
            $table->string("descrizione_architettura")->nullable();
            $table->string("traino")->nullable();
            $table->string("volumi")->nullable();
            $table->string("portata")->nullable();
            $table->string("posti_max")->nullable();
            $table->string("ricarica_standard")->nullable();
            $table->string("ricarica_veloce")->nullable();
            $table->string("pendenza_max")->nullable();
         //   $table->string("peso_potenza")->nullable();
            $table->string("descrizione_freni")->nullable();
            $table->string("peso")->nullable();
            $table->string("tipo_cons")->nullable();
            $table->string("emiss_urb")->nullable();
            $table->string("emiss_extraurb")->nullable();
            $table->string("tipo_guida")->nullable();
          //  $table->string("massa_p_carico")->nullable();
            $table->string("sosp_pneum")->nullable();
            $table->string("cap_serb_litri")->nullable();
            $table->string("cap_serb_kg")->nullable();
           // $table->string("peso_vuoto")->nullable();
            $table->string("paese_prod")->nullable();


            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('car_categories')->onDelete('cascade');
            $table->foreign('fuel_id')->references('id')->on('fuels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign('brand_id_foreign');
            $table->dropForeign('category_id_foreign');
            $table->dropForeign('fuel_id_foreign');
        });
        Schema::dropIfExists('cars');
    }
}
