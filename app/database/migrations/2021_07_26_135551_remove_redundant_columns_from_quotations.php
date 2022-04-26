<?php

use App\Quotation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRedundantColumnsFromQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('offer_id');
            $table->dropColumn('agent_id');
            $table->dropColumn('customer_id');
            $table->dropColumn('deposit');
            $table->dropColumn('monthly_rate');
            $table->dropColumn('duration');
            $table->dropColumn('distance');
            $table->dropColumn('franchise_insurance');
            $table->dropColumn('franchise_kasko');
            $table->dropColumn('change_tires');
            $table->dropColumn('car_replacement');
            $table->dropColumn('car_accessories');
            $table->dropColumn('bollo_auto');
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
            $table->unsignedInteger('offer_id')->after('stage');
            $table->unsignedInteger('agent_id')->after('offer_id');
            $table->unsignedInteger('customer_id')->after('agent_id');
            $table->decimal('deposit', 8, 2)->after('crm_id');
            $table->decimal('monthly_rate', 8, 2)->after('deposit');
            $table->tinyInteger('duration')->after('monthly_rate');
            $table->integer('distance')->after('duration');
            $table->string('franchise_insurance')->nullable()->after('distance');
            $table->string('franchise_kasko')->nullable()->after('franchise_insurance');
            $table->boolean('change_tires')->default(false)->after('franchise_kasko');
            $table->boolean('car_replacement')->default(false)->after('change_tires');
            $table->jsonb('car_accessories')->nullable()
                    ->comment('Elenco degli optional richiesti dal cliente')->after('car_replacement');
            $table->boolean('bollo_auto')->default(false)->after('car_accessories');
        });

        $this->fillAddedColumns();
    }

    protected function fillAddedColumns()
    {
        $quotations = Quotation::with('proposal')->get();

        foreach ($quotations as $quotation) {
            $quotation->update([
                'offer_id' => $quotation->proposal->offer_id,
                'agent_id' => $quotation->proposal->agent_id,
                'customer_id' => $quotation->proposal->customer_id,
                'deposit' => $quotation->proposal->deposit,
                'monthly_rate' => $quotation->proposal->monthly_rate,
                'duration' => $quotation->proposal->duration,
                'distance' => $quotation->proposal->distance,
                'franchise_insurance' => $quotation->proposal->franchise_insurance,
                'franchise_kasko' => $quotation->proposal->franchise_kasko,
                'change_tires' => $quotation->proposal->change_tires,
                'car_replacement' => $quotation->proposal->car_replacement,
                'car_accessories' => $quotation->proposal->car_accessories,
                'bollo_auto' => $quotation->proposal->bollo_auto,
            ]);
        }
    }
}
