<?php
namespace App\Factories;

use App\Abstracts\Crm\Crm;
use App\Abstracts\Crm\Factory;
use App\Models\Agent;
use App\Common\Models\Crm\Clients\Pipedrive;
use App\Common\Models\Crm\IDeal as iDealCrm;
use App\Common\Models\Crm\Offline;
use App\Common\Models\Crm\RentMax;
use App\Abstracts\Crm\Client;
use App\Models\CrmConnection;
use App\Models\Group;
use App\Models\Offer;
use App\Models\Quotation;

class CrmFactory extends Factory
{
    /**
     * @inheritDoc
     */
    static function create(Quotation $quotation): Crm
    {
        $connection = self::getConnection($quotation);
        $client = new Pipedrive($connection);
        return self::createCrmInstance($client, $connection);
    }

    /**
     * Crea un'istanza di ogetto crm
     * @param Client $client
     * @param CrmConnection $connection
     * @return iDealCrm|RentMax
     */
    private static function createCrmInstance(Client $client, CrmConnection $connection): Crm
    {
        switch (strtolower($connection->name)){
            case 'rentmax':
                $crm = new RentMax($client);
                break;
            case 'offline':
                $crm = new Offline($client);
                break;
            default:
                $crm = new iDealCrm($client);
                break;
        }

        return $crm;
    }

    /**
     * Ritorna una conessione partendo da una quotazione
     * @param Quotation $quotation
     * @return CrmConnection
     */
    private static function getConnection(Quotation $quotation): CrmConnection
    {

        /** @var Agent $agent */
        $agent = $quotation->proposal->agent;

        try {
            /** @var Group $group */
            $group = $agent->myGroup;

            $crmSettings = $group->crm_settings;

            if( empty($crmSettings) ) {
                return self::getDefaultConnection();
            } else {
                $connectionId = $crmSettings->connection;

                if(!empty($crmSettings->rules)){
                    //estraggo le regole
                    $rules = (array)$crmSettings->rules;

                    //controllo se la quatazione rispetta le regole della conessione
                    if ( !self::checkRules($rules, $quotation) )
                        return self::getDefaultConnection();
                }
                return CrmConnection::find($connectionId);
            }

        } catch (\Exception $exception) {
            $connection = self::getDefaultConnection();
        }

        return $connection;
    }

    /**
     * Controlla se una determinata quotazione rispeta un set di regole
     * @param array $rules
     * @param Quotation $quotation
     * @return bool
     */
    private static function checkRules(array $rules, Quotation $quotation): bool
    {
        $pass = TRUE;

        /** @var Offer $offer */
        $offer = $quotation->proposal->offer;

        if (!empty($rules['brokers'])){
            $brokerRules = $rules['brokers'];
            $pass &= in_array($offer->broker, $brokerRules);
        }

        //Gestisco il caso in cui il broker sia il gruppo stesso
        if(!$pass){
            /** @var Agent $agent */
            $agent = $quotation->proposal->agent;
            $customBroker = $agent->myGroup->name;
            $pass = $offer->broker == $customBroker;
        }

        return $pass;
    }

    /**
     * Crea una connessione utilizando i dati di default
     * @return CrmConnection
     */
    private static function getDefaultConnection(): CrmConnection
    {
        $connection = new CrmConnection;
        $connection->name = "DEFAULT CONNECTION";
        $connection->driver = CrmConnection::$DEFAULT_DRIVE;
        $connection->uri = config('services.pipedrive.uri');
        $connection->token = config('services.pipedrive.token');
        $connection->owner = config('services.pipedrive.owner_id');

        return $connection;
    }
}
