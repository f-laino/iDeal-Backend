<?php
namespace App\Common\Models\Crm;

use App\Abstracts\Crm\Crm;
use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Common\Models\Crm\Responses\Pipedrive as PipedriveResponse;
use App\Interfaces\Crm\Response;
use App\Models\ContractualCategory;
use App\Models\Customer;
use App\Models\Customer as CustomerModel;
use App\Models\Fuel;
use App\Models\Group;
use App\Models\Offer;
use App\Models\Quotation;
use SplFileInfo;
use Illuminate\Support\Facades\App;

class IDeal extends Crm
{

    /**
     * @inheritDoc
     */
    public $customerFields = [
        "name" => "name",
        "owner_id" => "owner_id",
        "org_id" => "org_id",
        "email" => "email",
        "phone" => "phone",
        "visible_to" => "visible_to",
        "address" => "be0733b7722dbcbffa0c519965af18bbaad14932",
        "fiscal_code" => "286018265854db941614c7eb964377ae98207a1f",
        "vat_number" => "bf2eedc1cee113bce7a175a1d5fb68d9f90cd576",
        "company_name" => "b05a4a67da780fc703c08b700d0948ca60610d8b",
        "contractual_category" => "60f9c10bae32d233d735fda1fa1a7f3afbf7ba74",
        "tempo_consegna_richiesto" => "a8328afe7f73094f0b1465f40a131182c8a9c013",
        "garante" => "5774fe998fe6edbe23160ac28aa5a8c5cffd30ff",
        "altro_intestatario" => "a08d304b7f490aa036a9fbcab4a7970897f6f0dd",
        "protestato" => "4faf9bacd6f56dce3926eb1dbadd925eaf8f5cc2",
        "reddito_netto_mensile" => "889040dfe6d565ea902342bef08c8a5258f88888",
        "prestiti_personali" => "3fb90f5fcc18fe87373eec8bd4f15df494551ba1",
        "fatturato_annuo" => "88bbdf1c866652bf0cae3ab40538397d4f7a9d70",
        "utile_annuo" => "4bf286190b72252fbfff53f59f81574220464260",
        "rata_sostenibile" => "4298bec9bf3028b0d9f52430766370adfab8d272",
    ];

    /**
     * @inheritDoc
     */
    public $dealFields = [
        "title" => "title",
        "user_id" => "user_id",
        "person_id" => "person_id",
        "org_id" => "org_id",
        "stage_id" => "stage_id",
        "status" => "status",
        "visible_to" => "visible_to",
        "value" => "value",
        "active" => "active",
        "deleted" => "deleted",
        "lost_reason" => "lost_reason",
        "brand" => "24457430cb5492928d759a63263548570d33fa2d",
        "model" => "0dc28262bcf8b1aa81ce349c8c2810d59c4f3bbf",
        "source" => "8deac6cf5f9debfa4364ee0453975897c36b8d20",
        "referrer" => "189a1a7863d3ca6b02492f12885a99cfb8236136",
        "medium" => "22f86c0f186baeb5be4ac5f459ad93748146376d",
        "documenti_inviati_intermediario" => "b46017cd82577d4c4bb96f649f1f8d4903856a0a",
        "anticipo" => "fcab4389284d610a96509c3270fea8452282b59d",
        "durata" => "141f9ce569227a1533b2ebdaf4827d1b6f24ec14",
        "distanza" => "647de315ffd074a6bd2d9c402a53ccf085d82503",
        "canone_mensile_no_iva" => "e326f4915b5135977c8d4328a26f5cc69b96d424",
        "note" => "7a038512cb6da5ec171c27d5ec754bb53efd3802",
        "franchigia_kasko" => "885ac7290537f283cab9f5b55b58c76b2f45d22b",
        "franchigia_furto_incendio" => "b0c5c2209a1ce81935c0312b58f0aa27ae499d74",
        "auto_sostitutiva" => "560a13164b9cb5378df3d802a7010f0fd16ba4b5",
        "cambio_gomme" => "a4d2f933cc0015d45374c410edec8aa43a9ed6d2",
        "bollo_auto" => "20c61f3f0fb17bd2812c7ac4a79f9165b15ab2df",
        "optional_richiesti" => "a83395d56a2a4f522f2a297a69eb3696ab8da8d3",
        "durata_richiesta" => "f32462b9410c6c5aa6c7ec275b7ebaf5dc190a0f",
        "anticipo_richiesto" => "525618a0c3bdfbbe339988cf0d16a056ffd7310d",
        "nuovo_canone_relativo_anticipo" => "6f9f3fb8c11f2a18179129433479b876ede0b3a4",
        "km_anno_richiesti" => "95605c6c32ba11c2c8cce98f670f82483b9a1d28",
        "nuovo_anticipo" => "46640d7018d2736a5736cb10968f933fe8995613",
        "nuovo_canone_mensile" => "d5ab101072fa5d484fcc644b967ff6dc3c1403ea",
        "priorita" => "dc8894c5fa32d8000a432edeeafe2d73c7386286",
        "salesbot" => "1f0eccb34c0c6334d5d6ff3ec7887fa2446b5b16",
    ];

    /**
     * @inheritDoc
     */
    public $productFields = [
        "name" => "name",
        "prices" => "prices",
        "code" => "code",
        "visible_to" => "visible_to",
        "brand" => "a13117ecfb909b8d321085870fe19be2f4ad70d2",
        "model" => "81d50a2104ee2771fbeebc905cb5270ae4cb37d4",
        "broker" => "56047d748d678c38b934c499ace6b57804005807",
        "anticipo" => "4f681fa6c244fc01a40abcb3d87f9f04adf7c69d",
        "distanza" => "e004bde469b3fe984c0530bdf18c8544b2eb7501",
        "durata" => "5d234fc7f50600f569494959b629025428015b31",
        "allimentazione" => "570289fb7fe80860ec6b59d43d2dae743a90f16a",
    ];

    /**
     * @inheritDoc
     */
    public $organizationFields = [
        "name" => "name",
        "visible_to" => "visible_to",
        "owner_id" => "owner_id",
        "agente" => "40001c47d2695b4f063ea6fad1d0677dfc563abd",
        "email" => "b7b8a8663dfed1d764307dd854ada31833f3af28",
        "telefono" => "cbe6d3c36bf727b36df51a6d9b95c4ad343646f8",
    ];

    /**
     * @inheritDoc
     */
    public $dealStages = [
        1 => "Nuova Richiesta",
        2 => "Nuova Richiesta",
        4 => "Nuova Richiesta",
        9 => "Nuova Richiesta",
        10 => "Nuova Richiesta",
        11 => "Nuova Richiesta",
        3 => "Preventivo Inviato",
        5 => "Scoring",
        6 => "Attesa di Contratto",
        7 => "Attesa di Contratto",
        8 => "Completato",
    ];

    /**
     * @inheritDoc
     */
    public $dealMutableStages = [1, 9, 10, 11];

    /**
     * @inheritDoc
     */
    public $defaultStage = 1;

    /**
     * @inheritDoc
     */
    public function getCustomer(int $customerCrmId): Response
    {
        $request = $this->client->persons()->find($customerCrmId);
        return new PipedriveResponse($request);
    }

    /**
     * @inheritDoc
     */
    public function findCustomer(CustomerModel $customer): Response
    {
        $fields = [$this->customerFields['fiscal_code']];
        $options = ["limit" => 1, "exact_match" => TRUE];

        $request = $this->client->persons()->search($customer->fiscal_code, $fields, $options);
        return new PipedriveResponse($request);
    }

    /**
     * Cerca un cliente all'interno del CRM e ritorna il suo id.
     * Nel caso in cui non ci fosse alcun risulato viene aggiunto uno nuovo customer
     * e ritornato l'id dell'oggetto appena creato.
     * @param CustomerModel $customer
     * @return int
     */
    private function findOrCreateCustomer(CustomerModel $customer): int
    {
        $search = $this->findCustomer($customer);
        $search = $search->getData();
        $search = $search->items;
        $crm_id = NULL;

        if (empty($search)) {
            $request = $this->createCustomer($customer);
            $crm_id = $request->getData()->id;
        } else {
            $search = reset($search);
            $item = $search->item;
            $crm_id = $item->id;
        }

        return $crm_id;
    }

    /**
     * @inheritDoc
     */
    public function createCustomer(CustomerModel $customer): Response
    {

        /** @var ContractualCategory $category */
        $category = $customer->category;

        $person = [
            $this->customerFields['name'] => $customer->name,
            $this->customerFields['owner_id'] => $this->owner,
            $this->customerFields["org_id"] => null,
            $this->customerFields['email'] => $customer->email,
            $this->customerFields['phone'] => $customer->phone,
            // 1:Owner & followers  | 3: Entire company
            $this->customerFields['visible_to'] => 3,
            //address
            $this->customerFields["address"] => $customer->address . ' ' . $customer->zip_code,
            // Fiscal Code
            $this->customerFields["fiscal_code"] => $customer->fiscal_code,
            //Vat Number
            $this->customerFields["vat_number"] => $customer->vat_number,
            // Company Name
            $this->customerFields["company_name"] => $customer->business_name,
            // Contractual Category
            $this->customerFields["contractual_category"] => $category->description,
        ];

        $request = $this->client->persons()->add($person);
        return new PipedriveResponse($request);
    }

    /**
     * @inheritDoc
     */
    public function updateCustomer(CustomerModel $customer, array $fields): bool
    {
        $crm_id = $this->findOrCreateCustomer($customer);
        //converto i campi in valori reali
        $fields = $this->prepareUpdateStatement($this->customerFields, $fields);
        $request = $this->client->persons()->update($crm_id, $fields);

        $request = new PipedriveResponse($request);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function destroyCustomer(CustomerModel $customer): bool
    {
        $search = $this->findCustomer($customer);
        $search = $search->getData();
        $search = $search->items;
        $crm_id = NULL;

        if (empty($search))
            return TRUE;

        $search = reset($search);
        $item = $search->item;
        $crm_id = $item->id;

        $request = $this->client->persons()->delete($crm_id);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function getDeal(Quotation $quotation): Response
    {
        $request = $this->client->deals()->find($quotation->crm_id);
        return new PipedriveResponse($request);
    }

    protected function getSourceByEnv()
    {
        switch(App::environment()) {
            case 'test':
            case 'testing':
                return 'UNIT-TEST';
            case 'dev':
            case 'local':
            case 'staging':
                return 'STAGING-TEST';
            default:
                return 'iDEAL';
        }
    }

    /**
     * @inheritDoc
     */
    public function createDeal(Quotation $quotation): Response
    {

        /** @var Offer $offer */
        $offer = $quotation->proposal->offer;

        /** @var Customer $customer */
        $customer = $quotation->proposal->customer;
        /** @var Car $car */
        $car = $offer->car;
        /** @var Brand $brand */
        $brand = $car->brand;
        /** @var Agent $agent */
        $agent = $quotation->proposal->agent;
        /** @var Group $group */
        $group = $agent->myGroup;

        //handle customer
        $customerId = $this->findOrCreateCustomer($customer);

        //handle organization
        $organizationId = $this->findOrCreateOrganization($agent);

        $accessories = (array)$quotation->proposal->car_accessories;

        $deal = [
            $this->dealFields["title"] => $brand->name . " " . $car->modello . " ( iDEAL )",
            $this->dealFields["user_id"] => $this->owner,
            $this->dealFields["person_id"] => $customerId,
            $this->dealFields["org_id"] => $organizationId,
            // 1 Nuova Richiesta
            $this->dealFields["stage_id"] => $this->defaultStage,
            $this->dealFields["status"] => "open",
            // 1:Owner & followers  | 3: Entire company
            $this->dealFields["visible_to"] => 3,
            $this->dealFields["value"] => $quotation->proposal->monthly_rate,
            $this->dealFields["active"] => true,
            $this->dealFields["deleted"] => false,
            //Car Brand
            $this->dealFields["brand"] => $brand->name,
            //Car Model
            $this->dealFields["model"] => $car->modello . ' ' . $car->allestimento,
            //Source
            $this->dealFields["source"] => $this->getSourceByEnv(),
            //Referrer
            $this->dealFields["referrer"] => $group->name,
            //Medium
            $this->dealFields["medium"] => $agent->getName(),
            //Documenti inviati dall’intermediario
            $this->dealFields['documenti_inviati_intermediario'] => 'No',
            //Anticipo
            $this->dealFields['anticipo'] => $quotation->proposal->deposit,
            //Durata (mesi), valori possibili 12, 24, 36, 48, 60
            $this->dealFields['durata'] => $this->getDurationId($quotation),
            //“Km/anno”, valore numerico
            $this->dealFields['distanza'] => $quotation->proposal->distance,
            //“Canone mensile IVA esclusa” (è il prezzo che vede il segnalatore), valore numerico,
            $this->dealFields['canone_mensile_no_iva'] => $quotation->proposal->monthly_rate,
            //Note
            $this->dealFields['note'] => $quotation->proposal->notes,
            //“Franchigia KASKO”
            $this->dealFields['franchigia_kasko'] => $quotation->proposal->franchise_kasko,
            //“Franchigia Furto e Incendio”
            $this->dealFields['franchigia_furto_incendio'] => $quotation->proposal->franchise_insurance,
            //add optional fields
            //“Auto sostitutiva”, valori possibili “Sì”, “No”,
            // TODO sostituire con gestione dinamica
            $this->dealFields['auto_sostitutiva'] => !empty($quotation->proposal->car_replacement) ? 'Si' : 'No',
            //“Cambio gomme”, valori possibili “Sì”, “No”
            // TODO sostituire con gestione dinamica
            $this->dealFields['cambio_gomme'] => !empty($quotation->proposal->change_tires) ? 'Si' : 'No',
            //Elenco optional richiesti
            $this->dealFields['optional_richiesti'] => $accessories,
        ];

        //handle product
        $request = $this->client->deals()->add($deal);
        $request = new PipedriveResponse($request);

        if ($request->isSuccess()) {
            //get crm ids
            $dealId = $request->getEntityId();
            $quotation->update([ "crm_id" => $dealId ]);

            $productId = $this->findOrCreateProduct($offer);

            //attach product to existing deal
            $this->client->deals()->addProduct($dealId, $productId, $quotation->proposal->monthly_rate, 1);
        }


        return $request;
    }

    /**
     * Ritorna value id delle option select di Pipedrive
     * @param Quotation $quotation
     * @return int
     */
    protected function getDurationId(Quotation $quotation)
    {
        //Mapping usando i valori di pipedrive
        if ($quotation->proposal->duration == 12)
            $duration = 105;
        elseif ($quotation->proposal->duration == 24)
            $duration = 106;
        elseif ($quotation->proposal->duration == 36)
            $duration = 107;
        elseif ($quotation->proposal->duration == 48)
            $duration = 108;
        elseif ($quotation->proposal->duration == 60)
            $duration = 109;
        else
            $duration = $quotation->proposal->duration;

        return $duration;
    }

    /**
     * @inheritDoc
     */
    public function updateDeal(Quotation $quotation, array $fields): bool
    {
        //converto i campi in valori reali
        $fields = $this->prepareUpdateStatement($this->dealFields, $fields);

        $request = $this->client->deals()->update($quotation->crm_id, $fields);
        $request = new PipedriveResponse($request);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function destroyDeal(Quotation $quotation): bool
    {
        return $this->client->deals()->delete($quotation->crm_id);
    }

    /**
     * @inheritDoc
     */
    public function getOrganization(int $organizationCrmId): Response
    {
        $request = $this->client->organizations()->find($organizationCrmId);
        return new PipedriveResponse($request);
    }


    /**
     * @inheritDoc
     */
    public function findOrganization(Agent $agent): Response
    {
        $fields = ["name"];
        $options = ["limit" => 1, "exact_match" => TRUE];
        $term = $agent->getName();
        $request = $this->client->organizations()->search($term, $fields, $options);
        return new PipedriveResponse($request);
    }

    /**
     * Cerca un'organizzazione all'interno del CRM e ritorna il suo id.
     * Nel caso in cui non ci fosse alcun risulato viene aggiunto una nuova organizzazione
     * e ritornato l'id dell'oggetto appena creato.
     * @param Agent $agent
     * @return int
     */
    private function findOrCreateOrganization(Agent $agent): int
    {
        $search = $this->findOrganization($agent);
        $search = $search->getData();
        $search = $search->items;
        $crm_id = NULL;

        if (empty($search)) {
            $request = $this->createOrganization($agent);
            $crm_id = $request->getData()->id;
        } else {
            $search = reset($search);
            $item = $search->item;
            $crm_id = $item->id;
        }

        return $crm_id;
    }

    /**
     * @inheritDoc
     */
    public function createOrganization(Agent $agent): Response
    {
        $organization = [
            $this->organizationFields['name'] => $agent->getName(),
            // 1:Owner & followers  | 3: Entire company
            $this->organizationFields['visible_to'] => 3,
            $this->organizationFields['owner_id'] => $this->owner,
            $this->organizationFields['email'] => $agent->email,
            $this->organizationFields['telefono'] => $agent->phone,
            $this->organizationFields['agente'] => $agent->name,
        ];
        $request = $this->client->organizations()->add($organization);
        return new PipedriveResponse($request);
    }

    /**
     * @inheritDoc
     */
    public function updateOrganization(Agent $agent, array $fields): bool
    {
        $crm_id = $this->findOrCreateOrganization($agent);
        //converto i campi in valori reali
        $fields = $this->prepareUpdateStatement($this->organizationFields, $fields);

        $request = $this->client->organizations()->update($crm_id, $fields);
        $request = new PipedriveResponse($request);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function destroyOrganization(Agent $agent): bool
    {
        $search = $this->findOrganization($agent);
        $search = $search->getData();
        $search = $search->items;
        $crm_id = NULL;

        if (empty($search))
            return TRUE;

        $search = reset($search);
        $item = $search->item;
        $crm_id = $item->id;

        $request = $this->client->organizations()->delete($crm_id);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function getProduct(int $offerCrmId): Response
    {
        $request = $this->client->products()->find($offerCrmId);
        return new PipedriveResponse($request);
    }

    /**
     * @inheritDoc
     */
    public function findProduct(Offer $offer): Response
    {
        $fields = ["code"];
        $options = ["limit" => 1, "exact_match" => TRUE];
        $code = $this->getProductCode($offer);
        $request = $this->client->products()->search($code, $fields, $options);
        return new PipedriveResponse($request);
    }

    /**
     * @inheritDoc
     */
    public function createProduct(Offer $offer): Response
    {
        /** @var Car $car */
        $car = $offer->car;
        /** @var Brand $brand */
        $brand = $car->brand;
        /** @var Fuel $fuel */
        $fuel = $car->fuel;

        $price = [
            "price"=> $offer->monthly_rate,
            "currency"=> "EUR",
            "cost"=> 0,
            "overhead_cost"=> null
        ];

        $product = [
            //Product Name
            $this->productFields["name"] =>  $this->getProductName($brand, $car),
            //Product Prices
            $this->productFields["prices"] => [ (object)$price ],
            //Product Code
            $this->productFields["code"] => $this->getProductCode($offer),
            // 1:Owner & followers  | 3: Entire company
            $this->productFields["visible_to"] => 3,
            //Car Brand
            $this->productFields["brand"] => $brand->name,
            //Car Model
            $this->productFields["model"] => $car->modello,
            //Broker
            $this->productFields["broker"] => $offer->broker,
            //Deposit
            $this->productFields["anticipo"] => $offer->deposit,
            //Distance
            $this->productFields["distanza"] => $offer->distance,
            //Duration
            $this->productFields["durata"] => $offer->duration,
            //Fuel
            $this->productFields["allimentazione"] => $fuel->name,
        ];

        $request = $this->client->products()->add($product);
        return new PipedriveResponse($request);
    }

    /**
     * Genera il codice di un prodotto
     * @param Offer $offer
     * @return string
     */
    private function getProductCode(Offer $offer){
        return "ideal-" . $offer->code;
    }

    /**
     * Genera il nome di un prodotto
     * @param Brand $brand
     * @param Car $car
     * @return string
     */
    private function getProductName(Brand $brand, Car $car){
        return 'iDEAL ' . $brand->name . ' ' . $car->modello . ' ' . $car->allestimento;
    }

    /**
     * Cerca un prodotto all'interno del CRM e ritorna il suo id.
     * Nel caso in cui non ci fosse alcun risulato viene aggiunto uno nuovo prodotto
     * e ritornato l'id dell'oggetto appena creato.
     * @param Offer $offer
     * @return int
     */
    private function findOrCreateProduct(Offer $offer): int
    {
        $search = $this->findProduct($offer);
        $search = $search->getData();
        $search = $search->items;
        $crm_id = NULL;

        if (empty($search)) {
            $request = $this->createProduct($offer);
            $crm_id = $request->getData()->id;
        } else {
            $search = reset($search);
            $item = $search->item;
            $crm_id = $item->id;
        }

        return $crm_id;
    }

    /**
     * @inheritDoc
     */
    public function updateProduct(Offer $offer, array $fields): bool
    {
        $crm_id = $this->findOrCreateProduct($offer);
        //converto i campi in valori reali
        $fields = $this->prepareUpdateStatement($this->productFields, $fields);

        $request = $this->client->products()->update($crm_id, $fields);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function destroyProduct(Offer $offer): bool
    {
        $search = $this->findProduct($offer);
        $search = $search->getData();
        $search = $search->items;
        $crm_id = NULL;

        if (empty($search))
            return TRUE;

        $search = reset($search);
        $item = $search->item;
        $crm_id = $item->id;

        $request = $this->client->products()->delete($crm_id);
        return $request->isSuccess();
    }

    /**
     * @inheritDoc
     */
    public function addFile(SplFileInfo $file, Customer $customer): Response
    {
        $searchCustomer = $this->findCustomer($customer);
        $searchCustomer = $searchCustomer->getData();
        $searchCustomer = $searchCustomer->items;

        if (empty($searchCustomer)) {
            throw new \Exception('Customer not found');
        }

        $searchCustomer = reset($searchCustomer);
        $item = $searchCustomer->item;
        $customer_crm_id = $item->id;

        $request = $this->client->files()->add([
            "file" => $file,
            "person_id" => $customer_crm_id
        ]);

        return new PipedriveResponse($request);
    }

    /**
     * Converte gli alias dei campi in campi reali usando i valori di mappatura
     * @param array $fields
     * @param array $values
     * @return array
     */
    private function prepareUpdateStatement(array $fields, array $values){
        $payload = [];

        foreach ($values as $key=>$value){
            $item = $this->getFieldKey($fields, $key);
            $payload[$item] = $value;
        }

        return $payload;
    }

    /**
     * @inheritDoc
     */
    public function transformStage($crm_stage): int
    {
       switch($crm_stage){
           case 8:
               return 5;
               break;
           case 7:
           case 6:
               return 4;
               break;
           case 5:
               return 3;
               break;
           case 3:
               return 2;
               break;
           default:
               return 1;
               break;
       }
    }

}
