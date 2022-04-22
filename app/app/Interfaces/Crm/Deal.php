<?php
namespace App\Interfaces\Crm;
use App\Models\Quotation;

/**
 * Interface Deal
 * @package App\Interfaces\Crm
 */
interface Deal
{

    /**
     * Legge i dati relativi ad un deal
     * @param Quotation $quotation
     * @return Response
     */
    public function getDeal(Quotation $quotation): Response;

    /**
     * Crea un nuovo deal
     * @param Quotation $quotation
     * @return Response
     */
    public function createDeal(Quotation $quotation): Response;

    /**
     * Aggiorana i dati relativi ad un deal
     * @param Quotation $quotation
     * @param array $fields
     * @return bool
     */
    public function updateDeal(Quotation $quotation, array $fields): bool;

    /**
     * Elimina i dati relativo ad un deal.
     * Attenzione questa funzione potrebbe non eliminare definitivamente i dati,
     * per ulteriori informazioni leggere la policy del sistema crm di riferimento
     * @param Quotation $quotation
     * @return bool
     */
    public function destroyDeal(Quotation $quotation): bool;

    /**
     * Traduce il stage del CRM in uno stage esistente all'interno di iDEAL
     * @param $crm_stage
     * @return int
     */
    public function transformStage($crm_stage): int;
}
