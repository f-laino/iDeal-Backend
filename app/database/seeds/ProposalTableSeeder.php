<?php

use App\Quotation;
use App\Proposal;
use Illuminate\Database\Seeder;

class ProposalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotations = Quotation::get();

        foreach ($quotations as $quotation) {
            $proposal = new Proposal();
            $proposal->offer_id = $quotation->offer_id;
            $proposal->agent_id = $quotation->agent_id;
            $proposal->customer_id = $quotation->customer_id;
            $proposal->deposit = $quotation->deposit;
            $proposal->monthly_rate = $quotation->monthly_rate;
            $proposal->duration = $quotation->duration;
            $proposal->distance = $quotation->distance;
            $proposal->franchise_insurance = $quotation->franchise_insurance;
            $proposal->franchise_kasko = $quotation->franchise_kasko;
            $proposal->change_tires = $quotation->change_tires;
            $proposal->car_replacement = $quotation->car_replacement;
            $proposal->car_accessories = !empty($quotation->car_accessories) ? $quotation->car_accessories : null;
            $proposal->bollo_auto = $quotation->bollo_auto;
            $proposal->notes = $quotation->notes;

            $proposal->save();

            $quotation->update(['proposal_id' => $proposal->id]);
        }
    }
}
