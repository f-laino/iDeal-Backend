<?php
namespace App\Interfaces\Crm;
use App\Models\Customer;
use SplFileInfo;

/**
 * Interface File
 * @package App\Interfaces\Crm
 */
interface File
{
    /**
     * Aggiunge un file alla persona.
     * Attenzione questa funzione potrebbe caricare i dati in modo pubblico,
     * per ulteriori informazioni leggere la policy del sistema crm di riferimento sella gestione dei file
     * @param SplFileInfo $file
     * @param Customer $customer
     * @return Response
     */
    public function addFile(SplFileInfo $file, Customer $customer): Response;
}
