<?php

namespace App\Interfaces\Crm;
use App\Models\Customer as CustomerModel;

/**
 * Interface Customer
 * @package App\Interfaces\Crm
 */
interface Customer
{

    /**
     * Legge i dati relativi ad un customer
     * @param int $customerCrmId
     * @return Response
     */
    public function getCustomer(int $customerCrmId): Response;

    /**
     * Cerca un utente
     * @param CustomerModel $customer
     * @return Response
     */
    public function findCustomer(CustomerModel $customer): Response;

    /**
     * Crea un nuovo customer
     * @param CustomerModel $customer
     * @return Response
     */
    public function createCustomer(CustomerModel $customer): Response;

    /**
     * Aggiorana i dati relativi ad un customer
     * @param CustomerModel $customer
     * @param array $fields
     * @return bool
     */
    public function updateCustomer(CustomerModel $customer, array $fields): bool;

    /**
     * Elimina i dati relativo ad un contatto.
     * Attenzione questa funzione potrebbe non eliminare definitivamente i dati,
     * per ulteriori informazioni leggere la policy del sistema crm di riferimento
     * @param CustomerModel $customer
     * @return bool
     */
    public function destroyCustomer(CustomerModel $customer): bool;
}
