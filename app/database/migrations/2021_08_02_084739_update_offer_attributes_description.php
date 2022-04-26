<?php

use App\OfferAttributes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOfferAttributesDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_attributes', function (Blueprint $table) {
            OfferAttributes::where('value', 'solo_piva')
                            ->update([
                                'value' => 'piva',
                                'description' => OfferAttributes::$rentLabels['piva'],
                            ]);

            OfferAttributes::where('value', 'solo_privati')
                            ->update([
                                'value' => 'privati',
                                'description' => OfferAttributes::$rentLabels['privati'],
                            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_attributes', function (Blueprint $table) {
            OfferAttributes::where('value', 'piva')
                            ->update([
                                'value' => 'solo_piva',
                                'description' => 'Solo P.IVA',
                            ]);

            OfferAttributes::where('value', 'privati')
                            ->update([
                                'value' => 'solo_privati',
                                'description' => 'Solo Privati',
                            ]);
        });
    }
}
