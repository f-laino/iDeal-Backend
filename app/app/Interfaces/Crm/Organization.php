<?php

namespace App\Interfaces\Crm;
use App\Models\Agent;

/**
 * Interface Organization
 * @package App\Interfaces\Crm
 */
interface Organization
{

    /**
     * Legge i dati relativi ad un'organizzazione
     * @param int $organizationCrmId
     * @return Response
     */
    public function getOrganization(int $organizationCrmId): Response;


    /**
     * Cerca un'organizzazione
     * @param Agent $agent
     * @return Response
     */
    public function findOrganization(Agent $agent): Response;


    /**
     * Cread una nuova organizzazione
     * @param Agent $agent
     * @return Response
     */
    public function createOrganization(Agent $agent): Response;

    /**
     * Aggiorana i dati relativi ad un'organizzazione
     * @param Agent $agent
     * @param array $fields
     * @return bool
     */
    public function updateOrganization(Agent $agent, array $fields): bool;

    /**
     * Elimina i dati relativo ad un'organizzazione.
     * Attenzione questa funzione potrebbe non eliminare definitivamente i dati,
     * per ulteriori informazioni leggere la policy del sistema crm di riferimento
     * @param Agent $agent
     * @return bool
     */
    public function destroyOrganization(Agent $agent): bool;
}
