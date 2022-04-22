<?php

namespace App\Helpers\Models;
use App\Constants\HubSpot\AgentProperties;
use App\Helpers\Interfaces\HubSpotHelper;

/**
 * Class AgentHelper
 * @package App\Helpers\Models
 */
class AgentHelper implements HubSpotHelper
{

    /** @var string  */
    private $name;
    /** @var string  */
    private $businessName;
    /** @var string|null  */
    private $phone;
    /** @var string|null  */
    private $notes;


    /**
     * AgentHelper constructor.
     * @param string $name
     * @param string $businessName
     * @param string|NULL $phone
     * @param string|NULL $notes
     */
    public function __construct(
        string $name = NULL,
        string $businessName = NULL,
        string $phone = NULL,
        string $notes = NULL
        )
    {
        $this->name = $name;
        $this->businessName = $businessName;
        $this->phone = $phone;
        $this->notes = $notes;
    }

    /**
     * Trasforma un oggetto in proprieta hubspot
     * @return array
     */
    public function toHubSpotProprieties(): array
    {
        $props = [];

        //Hubspot non accetta valori NULL
        if(!empty($this->name))
            $props[AgentProperties::FIRST_NAME] = $this->name;

        if(!empty($this->businessName)) {
            $props[AgentProperties::COMPANY] = $this->businessName;
            $props[AgentProperties::RAGIONE_SOCIALE] = $this->businessName;
        }

        if(!empty($this->phone))
            $props[AgentProperties::PHONE_NUMBER] = $this->phone;

        if(!empty($this->notes))
            $props[AgentProperties::NOTE] = $this->notes;

        return $props;
    }

}
