<?php
namespace App\Abstracts\Crm;

use App\Models\Quotation;

/**
 * Class AbstractCrmFactory
 * @package App\Abstracts
 */
abstract class Factory
{
    /**
     * @param Quotation $quotation
     * @return Crm
     */
    abstract static function create(Quotation $quotation) : Crm;
}
