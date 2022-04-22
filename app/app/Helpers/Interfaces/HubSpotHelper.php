<?php


namespace App\Helpers\Interfaces;


interface HubSpotHelper
{

    /**
     * Trasforma un oggetto in proprieta hubspot
     * @return array
     */
    public function toHubSpotProprieties() : array;

}