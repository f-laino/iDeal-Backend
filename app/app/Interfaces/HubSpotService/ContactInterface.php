<?php
namespace App\Interfaces\HubSpotService;

use App\Helpers\Models\ConsentHelper;
use App\Helpers\Models\AgentHelper;
use App\Models\Agent;
use SevenShores\Hubspot\Http\Response as HubSpotResponse;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

interface ContactInterface
{

    /**
     * @param Agent $agent
     * @param string|NULL $attribute
     * @return mixed
     *
     * @see https://developers.hubspot.com/docs/methods/contacts/get_contact
     */
    public function getContact(Agent $agent, string $attribute = NULL);

    /**
     * Cerca un contato utilizzando l'indirizzo email
     * @param string $email
     * @param string|NULL $attribute
     * @return mixed
     *
     * @see https://developers.hubspot.com/docs/methods/contacts/get_contact_by_email
     */
    public function getContactByEmail(string $email, string $attribute = NULL);

    /**
     * @param Agent $agent
     * @param AgentHelper $helper
     * @param ConsentHelper $consent
     * @return Agent
     *
     * @see https://developers.hubspot.com/docs/methods/contacts/create_contact
     */
    public function createContact(Agent $agent, AgentHelper $helper, ConsentHelper $consent) : Agent;

    /**
     * @param Agent $agent
     * @param array $updated_properties
     * @return GuzzleResponse
     *
     * @see https://developers.hubspot.com/docs/methods/contacts/update_contact
     */
    public function updateContact(Agent $agent, $updated_properties=[]) : GuzzleResponse;

    /**
     * @param Agent $agent
     * @return GuzzleResponse
     *
     * @see https://developers.hubspot.com/docs/methods/contacts/delete_contact
     */
    public function deleteContact(Agent $agent ) : GuzzleResponse;

}
