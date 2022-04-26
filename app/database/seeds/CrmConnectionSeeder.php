<?php

use Illuminate\Database\Seeder;
use App\CrmConnection;

class CrmConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $iDeal = new CrmConnection();
        $iDeal->name = "IDEAL";
        $iDeal->driver = CrmConnection::$DEFAULT_DRIVE;
        $iDeal->uri = env('PIPEDRIVE_ENDPOINT');
        $iDeal->token = env('PIPEDRIVE_KEY');
        $iDeal->owner = env('PIPEDRIVE_OWNER_ID');
        $iDeal->save();
    }
}
