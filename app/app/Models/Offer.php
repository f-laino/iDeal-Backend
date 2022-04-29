<?php

namespace App\Models;

use App\Common\Models\DocumentList;
use App\Common\Models\Franchiagia;
use App\Traits\AttachableAccount;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Exception;

/**
 * Class Offer
 * @package App
 * @property integer $id
 * @property integer|null $parent_id
 * @property string $code
 * @property integer $car_id
 * @property float $monthly_rate
 * @property float|null $web_monthly_rate
 * @property float $deposit
 * @property integer $distance
 * @property integer $duration
 * @property string $broker
 * @property string $notes
 * @property boolean $suggested
 * @property integer|null $owner_id
 * @property boolean $is_custom
 * @property boolean $status
 * @property boolean $highlighted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Offer extends Model
{
    use SoftDeletes, AttachableAccount;

    protected $table = "offers";
    protected $guarded = ['id', 'deleted_at'];
    protected $dates = ['deleted_at'];

    /**
     * Elenco delle distanze di default divise per segmento
     * @var array
     */
    public static $MAX_DEPOSIT_BY_SEGMENT = [
        'A' => 2000,
        'B' => 3000,
        'C' => 5000,
        'D' => 15000,
        'E' => 20000,
    ];

    public static $ALLOWED_DURATIONS = [18, 24, 36, 48, 60];

    /**
     * Elenco dei broker noleggio
     * @var array
     */
    public static $BROKERS = [
        'ALD'=>'ALD',
        'Arval'=>'Arval',
        'Lease Plan' => 'Lease Plan',
        'Leasys' => 'Leasys',
        'Noleggio Volkswagen' => 'Noleggio Volkswagen',
        'Alphabet' => 'Alphabet',
        'sifa' => 'Sifa',
        'ekly' => 'Ekly'
    ];

    /**
     * Elenco delle distanze di default divise per segmento
     * @var array
     */
    public static $DISTANCES_BY_SEGMENT = [
        'A' => [10, 15, 20],
        'B' => [10, 15, 20],
        'C' => [15, 20, 25],
        'D' => [15, 20, 25],
        'E' => [15, 20, 25],
    ];

    /**
     * Ritorna l'associazione con l'allestimento
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    /**
     * Formatta la data di aggiornamento
     * @return string
     */
    public function getLastUpdateAttribute()
    {
        return Carbon::parse($this->updated_at)->format('H:i d-F-Y');
    }

    /**
     * Formatta la data di scadenza
     * @return string
     */
    public function getExpirationAttribute()
    {
        return Carbon::parse($this->expiration_date)->format('d-F-Y');
    }

    /**
     * Ritorna la descrizione associata
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function description()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where("type", "DESCRIPTION");
    }

    /**
     * Ritorna l'associazione con gli attributi dell'offerta
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributeList()
    {
        return $this->hasMany('App\Models\OfferAttributes', 'offer_id');
    }

    /**
     * Ritorna l'attributo right label
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rightLabel()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where('type', 'RIGHT_LABEL');
    }

    /**
     * Ritorna l'attributo left label
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leftLabel()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where('type', 'LEFT_LABEL');
    }

    /**
     * Ritorna l'attributo reference code
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function referenceCode()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where('type', 'REFERENCE_CODE');
    }

    /**
     * Ritorna l'attributo car color
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function color()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where('type', 'CAR_COLOR');
    }

    /**
     * Ritorna l'attributo delivery time
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function deliveryTime()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where('type', 'DELIVERY_TIME')->withDefault([
            'value' => false,
            'description' => ''
        ]);
    }

    /**
     * Ritorna l'attributo fast delivery
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fastDelivery()
    {
        return $this->hasOne('App\Models\OfferAttributes', 'offer_id')->where('type', 'FAST_DELIVERY')->withDefault([
            'value' => false
        ]);
    }

    /**
     * Ritorna il valore dell'attributo car colore
     * @return string
     */
    public function getColorName()
    {
        $color = "";
        try {
            $obj = $this->color;
            if (!empty($obj)) {
                $color = $obj->value;
            }
        } catch (Exception $exception) {
        }

        return $color;
    }

    /**
     * Ritorna l'associazione con i servizi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        if (empty($this->parent_id)) {
            return $this->belongsToMany('App\Models\Service');
        }
        return $this->belongsToMany('App\Models\Service', 'offer_service', 'offer_id', 'service_id', 'parent_id', 'id');
    }

    /**
     * Attach one service to the offer
     * @param int $serviceId
     * @return mixed
     */
    public function attachService($serviceId)
    {
        if (\DB::table('offer_service')->where([['service_id', $serviceId], [ 'offer_id', $this->id]])->exists()) {
            return true;
        }
        $timestamp = Carbon::now()->toDateTimeString();
        return  \DB::table('offer_service')->insert([
            'service_id' => $serviceId,
            'offer_id' => $this->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }

    /**
     * Attach a list of services to an offer
     * @param array $slugs
     * @return bool
     */
    public function attachServices(array $slugs)
    {
        $services = Service::findBySlugs($slugs);
        $status = true;
        foreach ($services as $service) {
            $status &= $this->attachService($service->id);
        }
        return $status;
    }

    /**
     * Attach all available services to an offer
     * @return bool
     */
    public function attachAllServices()
    {
        $serviceList = Service::all();
        $status = true;
        foreach ($serviceList as $service) {
            $status &= $this->attachService($service->id);
        }
        return $status;
    }

    /**
     * Associa tutti i servizi di default ad un offerta
     * @return bool
     */
    public function attachDefaultServices()
    {
        $serviceList = Service::getDefaultServices();
        $status = true;
        foreach ($serviceList as $service) {
            $status &= $this->attachService($service->id);
        }
        return $status;
    }

    /**
     * Distacca un servizio dall'offerta
     * @param int $serviceId
     * @return mixed
     */
    public function detachService($serviceId)
    {
        return  \DB::table('offer_service')->where('offer_id', $this->id)->where('service_id', $serviceId)->delete();
    }

    /**
     * Detach all offer services
     * @return mixed
     */
    public function detachAllServices()
    {
        return  \DB::table('offer_service')->where('offer_id', $this->id)->delete();
    }

    /**
     * Ritoran l'elenco di agenti che hanno accesso a questa offerta
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function agents()
    {
        return $this->belongsToMany('App\Models\Agent', 'agent_offer');
    }

    /**
     * Attach agent to an offer
     * @param int $agentId
     * @param boolean $status
     * @return mixed
     * @deprecated
     */
    public function attachAgent($agentId, $status = true)
    {
        $timestamp = Carbon::now()->toDateTimeString();
        if (\DB::table('agent_offer')->where([['agent_id', $agentId], [ 'offer_id', $this->id]])->exists()) {
            return true;
        }

        return  \DB::table('agent_offer')->insert([
            'agent_id' => $agentId,
            'offer_id' => $this->id,
            'status' => $status,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }


    /**
     * Associa l'offerta a tutti gli agenti
     * @param bool $status
     * @return bool|mixed
     */
    public function attachAllAgents($status = true)
    {
        $agents = Agent::getEnabledAccounts();
        return $this->attachAgents($agents, $status);
    }

    /**
     * Attach one child offer to the main offer
     * @param int $parentId
     * @param Offer $child
     * @return mixed
     */
    public function attachChild($parentId, $child)
    {
        $childOffer = new Offer();
        $childOffer->parent_id = $parentId;
        $childOffer->code = $child->code;
        $childOffer->broker = $child->broker;
        $childOffer->car_id = $child->car_id;
        $childOffer->deposit = $child->deposit;
        $childOffer->duration = $child->duration;
        $childOffer->distance = $child->distance;
        $childOffer->monthly_rate = $child->monthly_rate;
        $childOffer->web_monthly_rate = $child->web_monthly_rate;
        $childOffer->owner_id = $child->owner_id;
        $childOffer->is_custom = $child->is_custom;
        $childOffer->status = $child->status;
        $childOffer->suggested = $child->suggested;
        $childOffer->highlighted = $child->highlighted;
        $childOffer->created_at = Carbon::now()->toDateTimeString();

        return $childOffer->saveOrFail();
    }

    /**
     * Attach a list of child offers to a main offer
     * @param int $parentId
     * @param Collection $childs
     * @return bool|mixed
     */
    public function attachChilds(int $parentId, Collection $childs)
    {
        $result = true;

        foreach ($childs as $child) {
            $result &= $this->attachChild($parentId, $child);
        }

        return $result;
    }

    /**
     * @return float
     */
    public function getRealPrice()
    {
        return (float)$this->monthly_rate;
    }

    /**
     * Ritorna le offerte child
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childOffers()
    {
        return $this->hasMany('App\Models\Offer', 'parent_id');
    }


    /**
     * Determina se l'offerta attuale ha delle offerte figlie
     * @return bool
     */
    public function hasChildOffers()
    {
        return $this->childOffers()->exists();
    }

    /**
     * Ritorna il numero delle offerte figlie
     * @param bool|NULL $status
     * @return int
     */
    public function countChildOffers(bool $status = null)
    {
        $childOffers = $this->childOffers();
        if (!is_null($status)) {
            $childOffers->where('status', $status);
        }
        return $childOffers->count();
    }

    /**
     * Ritorna la query per tutte le offerte dato un determinato broker
     * @param int|null $broker
     * @param bool $onlyMain
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getByBroker($broker = null, $onlyMain = true)
    {
        return  $onlyMain ?
            self::whereNull('parent_id')->where('broker', $broker) :
            self::whereNotNull('parent_id')->where('broker', $broker);
    }

    public function generateCloneCode()
    {
        return strtolower($this->code . "-" . str_random(4));
    }

    /**
     * Genero tutte le offerte child partendo da un offerta base
     * @param PriceIndex $priceIndex La matrice contenete le viariazioni di prezzo in base ai parametri dell'offerta
     * @return bool|\Exception
     * @throws \Throwable
     * @deprecated
     */
    public function generateChildOffers(PriceIndex $priceIndex)
    {
        throw_if(
            $this->broker != $priceIndex->broker || $this->car->segmento != $priceIndex->segment,
            new \InvalidArgumentException('PriceIndexer is not compatible with this offer.')
        );


        try {
            self::where('parent_id', $this->id)->delete();
        } catch (\Exception $exception) {
            return $exception;
        }

        $priceMatrix = json_decode($priceIndex->pattern, true);
        $webPriceMatrix = json_decode($priceIndex->secondary_pattern, true);

        $parentDuration = $this->duration;
        $km_anno = $this->distance / 1000;
        $parentDeposit = $this->deposit > 0 ? 1 : 0;

        $basePrice = $priceMatrix["$parentDeposit&$km_anno&$parentDuration"];
        $webBasePrice = $webPriceMatrix["$parentDeposit&$km_anno&$parentDuration"];
        throw_if(is_null($basePrice) || is_null($webBasePrice), new \Exception("We are not able to find the main offer inside PriceIndexer, please update your offer or your price indexer list and retry again.", 1));
        unset($priceMatrix["$parentDeposit&$km_anno&$parentDuration"]); //rimuovo offerta esistente dalla cobminazione
        unset($webPriceMatrix["$parentDeposit&$km_anno&$parentDuration"]); //rimuovo offerta esistente dalla cobminazione

        foreach ($priceMatrix as $key => $value) {
            try {
                list($deposit, $distance, $duration) = explode('&', $key);
                $duration = intval($duration);
                $deposit = $deposit == '1' ? $this->deposit : 0;
                $distance = intval($distance) * 1000;
                //  $monthly_rate = intval(floatval($this->monthly_rate) * floatval($value/$basePrice));
                // $monthly_rate = intval(floatval($this->monthly_rate) / floatval($basePrice) * floatval($value));
                $monthly_rate = intval(floatval($value) / floatval($basePrice) * floatval($this->monthly_rate));
                $webValue = !empty($webPriceMatrix[$key]) ? $webPriceMatrix[$key] : 1; //valore della matrice secondaria
                //$web_monthly_rate = intval(floatval($this->web_monthly_rate) * floatval($webValue/$webBasePrice));
                // $web_monthly_rate = intval(floatval($this->web_monthly_rate) / floatval($webBasePrice) * floatval($webValue));
                $web_monthly_rate = $monthly_rate;


                $newOffer = new Offer;
                $newOffer->parent_id = $this->id;
                $newOffer->code = $this->code;
                $newOffer->broker = $this->broker;
                $newOffer->car_id = $this->car_id;
                $newOffer->deposit = $deposit;
                $newOffer->distance = $distance;
                $newOffer->duration = $duration;
                $newOffer->monthly_rate = $monthly_rate;
                $newOffer->web_monthly_rate = $web_monthly_rate;
                $newOffer->status = true;
                $newOffer->highlighted = false;

                $newOffer->saveOrFail();
            } catch (\Exception $exception) {
                return $exception;
            }
        }
        return true;
    }

    /**
     * Get the maximum deposit ammount available for this offer
     * @return int
     */
    public function getMaxDeposit()
    {
        return self::$MAX_DEPOSIT_BY_SEGMENT[$this->car->segmento];
    }

    /**
     * Ritorna l'elenco di distanze divisi per segmento auto
     * @return array
     */
    public function getDefaultDistanceBySegment()
    {
        return self::$DISTANCES_BY_SEGMENT[$this->car->segmento];
    }

    /**
     * Memorizza 11 offerte figlie
     * @param array $childOffers
     * @return bool|\Exception
     * @throws \Throwable
     * @deprecated
     */
    public function addChildOffers(array $childOffers)
    {
        throw_if(
            count($childOffers) != 11,
            new \InvalidArgumentException('Non &egrave; possibile aggiungere le multiofferte perch&eacute; ci sono duplicati fra i valori inseriti. La grandezza dei valori deve essere (4x3-1), dove 1 &egrave; il valore dell\'offerta main. La grandezza inserita &egrave; '. count($childOffers))
        );

        try {
            self::where('parent_id', $this->id)->delete();
        } catch (\Exception $exception) {
            return $exception;
        }

        foreach ($childOffers as $child) {
            try {
                $duration = intval($child["duration"]);
                $deposit = floatval($child['deposit']);
                $distance = intval($child['distance']);
                $monthly_rate = floatval($child['monthly_rate']);
                $this->addChildOffer($duration, $distance, $deposit, $monthly_rate);
            } catch (\Exception $exception) {
                return $exception;
            }
        }
        return true;
    }

    /**
     * Add a child offer
     * @param int $duration
     * @param int $distance
     * @param int $deposit
     * @param float $monthly_rate
     * @param float|NULL $web_monthly_rate
     * @param string|NULL $deleted_at
     * @return bool
     * @throws \Throwable
     */
    public function addChildOffer(int $duration, int $distance, int $deposit, float $monthly_rate, float $web_monthly_rate = null, string $deleted_at = null)
    {
        $newOffer = new Offer;
        $newOffer->parent_id = $this->id;
        $newOffer->code = $this->code;
        $newOffer->broker = $this->broker;
        $newOffer->car_id = $this->car_id;
        $newOffer->deposit = $deposit;
        $newOffer->duration = $duration;
        $newOffer->distance = $distance;
        $newOffer->monthly_rate = $monthly_rate;
        $newOffer->web_monthly_rate = $monthly_rate;
        $newOffer->owner_id = $this->owner_id;
        $newOffer->is_custom = $this->is_custom;
        $newOffer->status = $this->status;
        $newOffer->suggested = $this->suggested;
        $newOffer->highlighted = false;
        $newOffer->deleted_at = $deleted_at;

        if (!$this->highlighted) {
            $this->update(['highlighted' => true]);
        }

        $newOffer->saveOrFail();
        return $newOffer;
    }

    /**
     * Elimina tutte le figlie di un'offerta
     * @return mixed
     */
    public function deleteChilds()
    {
        $delete = $this->childOffers()->delete();
        $this->update(['highlighted' => false]);
        return $delete;
    }

    /**
     * Elimina una offerta figlia
     * @return mixed
     */
    public function deleteChild($id)
    {
        return $this->childOffers()->where('id', $id)->delete();
    }

    /**
     * Elimina un'offerta insieme alle offerte figlie, ai suoi agenti, servizi e attributi
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->deleteChilds();
        $this->detachAgents();
        $this->detachAllServices();
        OfferAttributes::deleteAllOfferAttributes($this);
        return parent::delete();
    }

    /**
     * Aggiorna il broker di un'offerta e delle sue figlie
     * @param string $broker
     * @return mixed
     */
    public function updateBroker(string $broker)
    {
        $this->update([ "broker" => $broker ]);
        return $this->updateChildsRecord("broker", $broker);
    }

    /**
     * Aggiorna i dati della vettura associata
     * @param int $carId
     * @return mixed
     */
    public function updateCarId(int $carId)
    {
        $this->update([ "car_id" => $carId ]);
        return $this->updateChildsRecord("car_id", $carId);
    }

    /**
     * Aggiorna i dati di tutte le offerte child
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function updateChildsRecord(string $name, $value)
    {
        return self::where("parent_id", $this->id)
            ->update([ $name => $value ]);
    }

    public function updateDeliveryTime(string $deliveryTime = null)
    {
        OfferAttributes::where([
            "offer_id" => $this->id,
            "type" => "DELIVERY_TIME"
        ])->delete();

        if ($deliveryTime) {
            $offerAttribute = new OfferAttributes();
            $offerAttribute->offer_id = $this->id;
            $offerAttribute->type = "DELIVERY_TIME";
            $offerAttribute->value = strtolower(str_replace(' ', '-', $deliveryTime));
            $offerAttribute->description = $deliveryTime;
            $offerAttribute->saveOrFail();
        }
    }

    public function updateFastDelivery($value)
    {
        OfferAttributes::where([
            "offer_id" => $this->id,
            "type" => "FAST_DELIVERY"
        ])->delete();

        if ($value) {
            $offerAttribute = new OfferAttributes();
            $offerAttribute->offer_id = $this->id;
            $offerAttribute->type = "FAST_DELIVERY";
            $offerAttribute->value = 1;
            $offerAttribute->saveOrFail();
        }
    }

    /**
     * Ritorna l'elenco delle franchigie dell'offerta
     * @return array
     */
    public function getFranchigie()
    {
        switch (strtolower($this->broker)){
            case 'arval':
                $franchigie = [
                    "rca" => new Franchiagia('rca', 250),
                    "kasko" => new Franchiagia('kasko', 500),
                    "f_i" => new Franchiagia('f_i', 500),
                ];
                break;
            case 'lease plan':
                $franchigie = [
                    "rca" => new Franchiagia('rca', 150),
                    "kasko" => new Franchiagia('kasko', 500),
                    "f_i" => new Franchiagia('f_i', 10, '%'),
                ];
                break;
            case 'ekly':
                $franchigie = [
                    "rca" => new Franchiagia('rca', 500),
                    "kasko" => new Franchiagia('kasko', 500),
                    "f_i" => new Franchiagia('f_i', 10, '%'),
                ];
                if ($this->hasService('copertura-assicurativa-totale')){
                    $franchigie = [
                        "rca" => new Franchiagia('rca', 0),
                        "kasko" => new Franchiagia('kasko', 0),
                        "f_i" => new Franchiagia('f_i', 0),
                    ];
                }
                break;
            default:
                $franchigie = [
                    "rca" => new Franchiagia('rca', 250),
                    "kasko" => new Franchiagia('kasko', 500),
                    "f_i" => new Franchiagia('f_i', 10, '%'),
                ];
                break;
        }

        return $franchigie;
    }

    /**
     * Ritorna l'elenco dei documenti richiesti dalla categoria contrattuale e dal broker
     * @param ContractualCategory $category
     * @return Collection
     */
    public function getMandatoryAttachments(ContractualCategory $category)
    {
        return DocumentList::getByContractualCategoryAndBroker((int)$category->id, $this->broker);
    }

    /**
     * Calcola il valore della commissione associata all'offerta. Tale commissione dipende dalla percentuale presente nel gruppo utente
     * La commissione e' gia inclusa nel prezzo dell'offerta.
     * @param Agent $agent
     * @return float
     */
    public function fee(Agent $agent)
    {
        // If is custom offer fee is zero
        if ($this->isCustom()) {
            return 0;
        }

        try {
            $fee = Fee::where('broker', strtolower($this->broker))->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return 0;
        }
        $pattern = json_decode($fee->pattern, true);
        $carSegment = !empty($this->car) ? strtolower($this->car->segmento) : 'a';

        $fee_percentage = 100;

        if ($agent->fee_percentage !== 0.0) {
            $fee_percentage = $agent->fee_percentage;
        } else {
            $group = $agent->myGroup;
            if (!empty($group)) {
                $fee_percentage = $group->fee_percentage;
            }
        }

        $feeValue = floatval($pattern["segment_$carSegment"]) * $fee_percentage / 100;
        $feeValue = ceil($feeValue);
        return floatval($feeValue);
    }

    /**
     * Cerca un'offerta (non figlia) partendo dal codice
     * @param string $code
     * @return mixed
     */
    public static function findByCode(string $code)
    {
        return self::where('code', $code)->whereNull('parent_id')->orderBy('id', 'DESC')->firstOrFail();
    }

    /**
     * Indica se un'offerta e gestita tramite il CRM di CarPlanner
     * @return bool
     */
    public function canSendToCrm()
    {
        return !$this->isCustom();
    }

    /**
     * Controlla se un'offerta e gestita da carplanner oppure ed estena a carplanner
     * Le offerte esterne a carplanner hanno un flusso differente rispetto a quelle gesitte da carplanner
     * @return bool
     */
    public function isCustom()
    {
        return $this->is_custom;
    }

    /**
     * Aggiorna lo status sia dell'offerta sia dell'associazione con agente
     * @param Agent $agent
     * @param bool $status
     * @return mixed
     */
    public function updateStatus(Agent $agent, $status = true)
    {
        if ($this->canBeUpdated($agent)) {
            $this->update(['status' => $status]);
        }
        return $this->updateAgentOfferStatus($agent, $status);
    }

    /**
     * Aggiorna lo status dell'associazione con agente
     * Update agent offer status
     * @param Agent $agent
     * @param bool $status
     * @return mixed
     */
    public function updateAgentOfferStatus(Agent $agent, $status = true)
    {
        return \DB::table('agent_offer')
                ->where([['agent_id', $agent->id], [ 'offer_id', $this->id]])
                ->update(['status' => $status]);
    }

    /**
     * Specify if an offer can be updated
     * @param Agent $agent
     * @return bool
     */
    public function canBeUpdated(Agent $agent)
    {
        return $this->isCustom() && $this->owner_id === $agent->id;
    }

    /**
     * Ritorna  le offerte che possono essere associate a tutti gli agenti
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSharedOffers($status = null)
    {
        $query = self::whereNull('parent_id')->where('is_custom', false);
        if (is_bool($status)) {
            $query->where('status', $status);
        }
        return $query->get();
    }

    /**
     * Ritorna le offerte caricate da un agente
     * @param Agent $agent
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getOwnedOffers(Agent $agent, $status = null)
    {
        $query = self::whereNull('parent_id')->where('owner_id', $agent->id);
        if (is_bool($status)) {
            $query->where('status', $status);
        }
        return $query->get();
    }

    /**
     * Rimuove l'associazione fra un'offerta ed tutte le promozioni attive
     * @return mixed
     */
    public function detachFromAllPromotions()
    {
        return \DB::table('promotion_offer')
            ->where('offer_id', $this->id)
            ->delete();
    }

    /**
     * Ritorna l'associazione con le promozioni
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function promotions()
    {
        return $this->belongsToMany('App\Models\Promotion', 'promotion_offer');
    }

    /**
     * Ottiene l'immagine promozionale
     * @return Image
     */
    public function getPromotionalImage()
    {
        /** @var Car $car */
        $car = $this->car;

        /** @var Image $image */
        $image = $car->promotionImage()->first();
        if (empty($image)) {
            $image = $car->firstImage();
        }
        if (empty($image) || !$image instanceof Image) {
            $image = new Image;
        }

        return $image;
    }

    /**
     * Ottiene l'immagine promozionale
     * @return Image
     */
    public function getNewsletterImage()
    {
        /** @var Car $car */
        $car = $this->car;

        /** @var Image $image */
        $image = $car->newsletterImage()->first();
        if (empty($image)) {
            $image = $car->firstImage();
        }
        if (empty($image) || !$image instanceof Image) {
            $image = new Image;
        }

        return $image;
    }

    /**
     * Indica se l'auto in offerta sara consegnata a domicilio
     * @return bool
     */
    public function getHomeDeliveryAttribute()
    {
        return $this->broker == self::$BROKERS['Arval'];
    }

    /**
     * Indica se un determinato servizio e' associato all'offerta
     * @param string $slug slug del serivzio
     * @return bool
     */
    public function hasService(string $slug)
    {
        try {
            $service = Service::findBySlug($slug);
            return \DB::table('offer_service')
                    ->where(
                        [
                        ['service_id', $service->id],
                        [ 'offer_id', $this->id]]
                    )->exists();
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Ritorna il parent di un'offerta.
     * Se l'offerta e gia parent ritorna se stessa
     * @param Offer $offer
     * @return Offer
     */
    public static function getParentOffer(Offer $offer)
    {
        if (empty($offer->parent_id)) {
            return $offer;
        }
        return self::withTrashed()->findOrFail($offer->parent_id);
    }

    /**
     * Cambia lo stato di un'offerta e delle sue variazioni
     * @param bool $newStatus
     * @return mixed
     */
    public function changeStatus(bool $newStatus)
    {
        $id = $this->id;
        return Offer::where('id', $id)
                ->orWhere('parent_id', $id)
                ->update([ "status" => $newStatus ]);
    }

    /**
     * Cambia il valore suggested di un'offerta e delle sue variazioni
     * @param bool $suggested
     * @return mixed
     */
    public function changeSuggested(bool $suggested)
    {
        $id = $this->id;
        return Offer::where('id', $id)
                ->orWhere('parent_id', $id)
                ->update([ "suggested" => $suggested ]);
    }

    /**
     * Indica se l'offerta appartiene ad ekly
     * @return bool
     */
    public function isEklyOffer(): bool
    {
        $broker = strtolower($this->broker);
        return $broker == 'ekly';
    }

    /**
     * @return int
     */
    public static function countSuggested(): int
    {
        return self::where('suggested', TRUE)->whereNull('parent_id')->count();
    }
}
