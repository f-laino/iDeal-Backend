<?php
namespace App\Common\Models\Crm;

use App\Abstracts\Crm\Crm;
use App\Models\Agent;
use App\Common\Models\Crm\Responses\Offline as OfflineResponse;
use App\Interfaces\Crm\Response;
use App\Models\Customer;
use App\Models\Customer as CustomerModel;
use App\Models\Offer;
use App\Models\Quotation;
use SplFileInfo;

class Offline extends Crm
{

    /**
     * @inheritDoc
     */
    public $customerFields = [];

    /**
     * @inheritDoc
     */
    public $dealFields = [];

    /**
     * @inheritDoc
     */
    public $productFields = [];

    /**
     * @inheritDoc
     */
    public $organizationFields = [];

    /**
     * @inheritDoc
     */
    public $dealStages = [];

    /**
     * @inheritDoc
     */
    public $dealMutableStages = [1];

    /**
     * @inheritDoc
     */
    public $defaultStage = 1;

    /**
     * @inheritDoc
     */
    public function getCustomer(int $customerCrmId): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function findCustomer(CustomerModel $customer): Response
    {
        return new OfflineResponse();
    }


    /**
     * @inheritDoc
     */
    public function createCustomer(CustomerModel $customer): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function updateCustomer(CustomerModel $customer, array $fields): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroyCustomer(CustomerModel $customer): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDeal(Quotation $quotation): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function createDeal(Quotation $quotation): Response
    {
        return new OfflineResponse();;
    }


    /**
     * @inheritDoc
     */
    public function updateDeal(Quotation $quotation, array $fields): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroyDeal(Quotation $quotation): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getOrganization(int $organizationCrmId): Response
    {
        return new OfflineResponse();
    }


    /**
     * @inheritDoc
     */
    public function findOrganization(Agent $agent): Response
    {
        return new OfflineResponse();
    }


    /**
     * @inheritDoc
     */
    public function createOrganization(Agent $agent): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function updateOrganization(Agent $agent, array $fields): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroyOrganization(Agent $agent): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getProduct(int $offerCrmId): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function findProduct(Offer $offer): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function createProduct(Offer $offer): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function updateProduct(Offer $offer, array $fields): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroyProduct(Offer $offer): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function addFile(SplFileInfo $file, Customer $customer): Response
    {
        return new OfflineResponse();
    }

    /**
     * @inheritDoc
     */
    public function transformStage($crm_stage): int
    {
        return 1;
    }
}
