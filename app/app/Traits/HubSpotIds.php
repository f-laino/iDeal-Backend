<?php

namespace App\Traits;

/**
 * Trait HubSpotIds
 * @package App\Traits
 */
trait HubSpotIds
{
    /**
     * Ritorna l'id dell'entita relativa ad HubSpot
     * @return mixed
     */
    public function getHubSpotId(){
        return $this->hubspot_id;
    }

    /**
     * Salva l'id dell'entita relativa ad HubSpot
     * @param int $id
     * @return mixed
     */
    public function storeHubSpotId(int $id){
        return $this->update(["hubspot_id" => $id]);
    }

    /**
     * Aggiorna l'id dell'entita relativa ad HubSpot
     * @param int $newId
     * @return mixed
     */
    public function updateHubSpotId(int $newId){
        return $this->update(["hubspot_id" => $newId]);
    }

    /**
     * Rimuove l'id dell'entita relativa ad HubSpot
     * @return mixed
     */
    public function removeHubSpotId(){
        return $this->update(["hubspot_id" => NULL]);
    }

    /**
     * Trova un'entita utilizzando l'id fornito da HubSpot
     * @param int $id
     * @return mixed
     */
    public static function findByHubSpotId(int $id){
        return self::where('hubspot_id', $id)->first();
    }
}