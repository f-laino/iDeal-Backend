<?php

namespace App\Interfaces\Crm;
use App\Models\Offer;

/**
 * Interface Customer
 * @package App\Interfaces\Crm
 */
interface Product
{

    /**
     * Legge i dati relativi ad un customer
     * @param int $offerCrmId
     * @return Response
     */
    public function getProduct(int $offerCrmId): Response;

    /**
     * Cerca un prodotto
     * @param Offer $offer
     * @return Response
     */
    public function findProduct(Offer $offer): Response;

    /**
     * Crea un nuovo prodotto
     * @param Offer $offer
     * @return Response
     */
    public function createProduct(Offer $offer): Response;

    /**
     * Aggiorana i dati relativi ad un prodotto
     * @param Offer $offer
     * @param array $fields
     * @return bool
     */
    public function updateProduct(Offer $offer, array $fields): bool;

    /**
     * Elimina i dati relativo ad un prodotto.
     * Attenzione questa funzione potrebbe non eliminare definitivamente i dati,
     * per ulteriori informazioni leggere la policy del sistema crm di riferimento
     * @param Offer $offer
     * @return bool
     */
    public function destroyProduct(Offer $offer): bool;
}
