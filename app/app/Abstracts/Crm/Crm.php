<?php
namespace App\Abstracts\Crm;

use App\Interfaces\Crm\Customer;
use App\Interfaces\Crm\Deal;
use App\Interfaces\Crm\Organization;
use App\Interfaces\Crm\Product;
use App\Interfaces\Crm\File;
use App\Interfaces\Crm\Fields;

/**
 * Class Crm
 * @package App\Abstracts\Crm
 */
abstract class Crm implements Customer, Organization, Product, Deal, File, Fields
{
    /**
     * @var \App\Interfaces\Crm\Client
     */
    protected $client;

    /**
     * Indicare l'user owner del crm
     * @var mixed|string
     */
    protected $owner;

    /**
     * Indica lo stage con il quale il deal entra per la prima volta nel crm
     * @var integer
     */
    public $defaultStage;

    /**
     * Indica la pipeline di riferimento di un deal.
     * Un crm di solito ha piu di una pipeline operativa
     * @var string|null
     */
    public $pipeline;

    /**
     * Elenco mapping campi organizzazione
     * @var array
     */
    public $organizationFields = [];

    /**
     * Elenco mapping campi customer
     * @var array
     */
    public $customerFields = [];

    /**
     * Elenco mapping campi prodotto
     * @var array
     */
    public $productFields = [];

    /**
     * Elenco mapping campi deal
     * @var array
     */
    public $dealFields = [];

    /**
     * Elenco mapping posizioni nella pipeline
     * @var array
     */
    public $dealStages = [];

    /**
     * Indica i stati che possano essere alterati.
     * I deal non possono essere sempre modificati se sono in alcuni stati.
     * Esempio: un deal non puo cambiare priorta se sta in scoring
     * @var array
     */
    public $dealMutableStages = [];


    /**
     * Crm constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client->getClient();
        $this->owner = $client->getOwner();
    }

    /**
     * @inheritDoc
     */
    public function getFieldKey(array $fields, $label): string
    {
       return $fields[$label];
    }

    /**
     * @inheritDoc
     */
    public function getFieldLabelByValue(array $labels, $value): string
    {
        return array_search($value, $labels);
    }
}
