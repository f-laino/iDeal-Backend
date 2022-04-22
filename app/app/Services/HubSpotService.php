<?php

namespace App\Services;
use App\Constants\HubSpot\AgentProperties;
use App\Helpers\Models\ConsentHelper;
use App\Helpers\Models\AgentHelper;
use App\Interfaces\HubSpotServiceInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Http\Response;

use App\Models\Agent;
use App\Interfaces\HubSpotService as HubSpotServiceInterfaces;

use SevenShores\Hubspot\Http\Response as HubSpotResponse;
use SevenShores\Hubspot\Factory;

use Log;

/**
 * Class HubSpotService
 * @package App\Services
 */
class HubSpotService implements
    HubSpotServiceInterface,
    HubSpotServiceInterfaces\ContactInterface
{
    private $hubspot;
    private static $portal;
    private static $customer_form;

    /**
     * HubSpotService constructor.
     * @param string $api_key
     * @param string $portal
     * @param string $customer_form
     */
    public function __construct(string $api_key, string $portal, string $customer_form){
        $this->hubspot = Factory::create($api_key, null,
            [
                'http_errors' => false // pass any Guzzle related option to any request, e.g. throw no exceptions
            ],
            false // return Guzzle Response object for any ->request(*) call);
        );
        self::$portal = $portal;
        self::$customer_form = $customer_form;
    }


    /**
     * Prepare i dati per la richiesta di salvataggio
     * @param Agent $agent
     * @param AgentHelper $helper
     * @return array
     */
    private function prepareContactRequest(Agent $agent, AgentHelper $helper, ConsentHelper $consent) : array
    {

        $properties = [
            AgentProperties::FIRST_NAME => $agent->name,
            AgentProperties::COMPANY => $agent->business_name,
            AgentProperties::EMAIL => strtolower($agent->email),
        ];
        $properties = $properties + $helper->toHubSpotProprieties();

        $form = [
            'fields' => $this->preprocessProperties($properties),
            'legalConsentOptions' => [
                "consent" => $consent->toHubSpotProprieties()
            ],
        ];
        return $form;
    }

    /**
     * Crea un contatto utilizzando l'indirizzo e-mail
     * @param Agent $agent
     * @return Agent
     */
    private function preprocessContact(Agent $agent){
        $response = $this->hubspot->contacts()->createOrUpdate($agent->email);
        $husbpotId = self::getResponseContent($response, 'vid');
        $agent->storeHubSpotId($husbpotId);
        return $agent;
    }


    public function createContact(Agent $agent, AgentHelper $helper, ConsentHelper $consent): Agent
    {
        //I form di hubspot funzionano in modo assincrono pertanto
        //prima di fare la submission di un form dobbiamo creare un contatto usando l'inidrizzo e-mail
        $user = $this->preprocessContact($agent);

        $params = $this->prepareContactRequest($agent, $helper, $consent);

        //Usiamo i from di hubspot cosi che possiamo utilizzare il tracciamento nativo ed altre funzionalita built-in
        //Hubspot form submission non ritorna l'id del contatto
        $response = $this->hubspot->forms()->submit(self::$portal, self::$customer_form, $params);
        $response = self::getResponseContent($response);
        return $user;
    }

    public function updateContact(Agent $agent, $updated_properties = []) : GuzzleResponse
    {
        $params = $this->preprocessParams($updated_properties);
        $response = $this->hubspot->contacts()->update($agent->getHubSpotId(), $params);
        return self::notifyIfError($response);
    }


    public function getContact(Agent $agent, string $attribute = NULL)
    {
        $hubspotDealId = $agent->getHubSpotId();
        $response = $this->hubspot->contacts()->getById($hubspotDealId);
        $husbpotObject = self::getResponseContent($response, $attribute);
        return $husbpotObject;
    }

    public function getContactByEmail(string $email, string $attribute = NULL)
    {
        $response =  $this->hubspot->contacts()->getByEmail($email);
        $husbpotObject = self::getResponseContent($response, $attribute);
        return $husbpotObject;
    }


    public function deleteContact(Agent $agent) : GuzzleResponse
    {
        $response = $this->hubspot->contacts()->delete($agent->getHubSpotId());
        $agent->removeHubSpotId();
        return self::notifyIfError($response);
    }

    /**
     * Fa il submit di un form hubspot
     * @param Agent $agent
     * @param string $hubspotFormId
     * @param array $hubspotFormFields
     * @param ConsentHelper|NULL $consent
     * @return bool
     */
    public function submitForm(Agent $agent, string $hubspotFormId, array $hubspotFormFields = [], ConsentHelper $consent = NULL){

        //Aggiungo il campo email utente al form
        $hubspotFormFields[AgentProperties::EMAIL] = strtolower($agent->email);

        //
        $form = [ 'fields' => $this->preprocessProperties($hubspotFormFields) ];

        //Aggiorna i consensi
        if(!is_null($consent))
            $form['legalConsentOptions'] = [ "consent" => $consent->toHubSpotProprieties() ];


        //Submit del form usando hubspot sdk
        $response = $this->hubspot->forms()->submit(self::$portal,$hubspotFormId, $form);

        //Ritorno esito richiesta
        return self::getResponseContent($response);
    }

    /**
     * Formatta i parametri delle richesta secondo il formato richiesto da HubSpot
     * @param array $properties
     * @return array
     */
    private function preprocessParams(array $properties) : array
    {
        $params = [];
        foreach ($properties as $key => $value )
            $params[] = ['property' => $key, 'value' => $value];
        return $params;
    }

    /**
     * @param array $properties
     * @return array
     */
    private function preprocessProperties(array $properties) : array
    {
        $params = [];
        foreach ($properties as $key => $value )
            $params[] = ['name' => $key, 'value' => $value];
        return $params;
    }


    public static function isResponseSuccessful(GuzzleResponse $response): bool
    {
        return $response->getStatusCode() === Response::HTTP_OK;
    }

    public static function isResponseSuccessfulButEmpty(GuzzleResponse $response): bool
    {
        return $response->getStatusCode() === Response::HTTP_NO_CONTENT;
    }

    public static function isResponseNotFound(GuzzleResponse $response): bool
    {
        return $response->getStatusCode() === Response::HTTP_NOT_FOUND;
    }


    /**
     * @param GuzzleResponse $response
     * @param string|NULL $attribute
     * @return mixed
     */
    public static function getResponseContent(GuzzleResponse $response, string $attribute = NULL)
    {
        if(!self::isResponseSuccessful($response)){
            Log::channel('hubspot')->critical("HubSpot response error: ",
                [
                    'code' => $response->getStatusCode(),
                    'error' => json_decode($response->getBody()->getContents()),
                ]
            );
        }
        $response = json_decode($response->getBody()->getContents());

        Log::channel('hubspot')->info("HubSpot API Response", ['response' => $response]);
        return is_null($attribute) ? $response : $response->{$attribute};
    }

    /**
     * Notifica un'eventuale errore ritornato da HubSpot
     * @param GuzzleResponse $response
     * @return GuzzleResponse
     */
    public static function notifyIfError(GuzzleResponse $response) : GuzzleResponse
    {
        if(!self::isResponseSuccessful($response) && !self::isResponseSuccessfulButEmpty($response)){
            Log::channel('hubspot')->critical("HubSpot response error: ",
                [
                    'code' => $response->getStatusCode(),
                    'error' => json_decode($response->getBody()->getContents()),
                ]
            );
        }
        return $response;
    }


}
