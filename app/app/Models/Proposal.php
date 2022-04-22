<?php

namespace App\Models;

use App\Models\CarAccessory;
use App\Events\ProposalCreated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Class Proposal
 * @package App
 * @property integer $id
 * @property integer $offer_id
 * @property integer $agent_id
 * @property integer $customer_id
 * @property float $deposit
 * @property float $monthly_rate
 * @property integer $duration
 * @property integer $distance
 * @property string|null $franchise_insurance
 * @property string|null $franchise_kasko
 * @property boolean $change_tires // deprecated
 * @property boolean $car_replacement // deprecated
 * @property string|null $car_accessories
 * @property string|null $copertura_assicurativa // unused
 * @property string|null $pai // deprecated
 * @property string|null $tutela_legale // deprecated
 * @property string|null $assistenza_stradale // deprecated
 * @property boolean $bollo_auto
 * @property string|null $notes
 * @property integer $print_count
 * @property Carbon|null $last_print_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Proposal extends Model
{
    use SoftDeletes;

    protected $fillable = ['notes'];

    protected $dates = ['last_print_at'];

    /**
     * Ritorna l'associazione con l'offerta
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function offer()
    {
        return $this->belongsTo('App\Models\Offer')->withTrashed();
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
        return $this->belongsToMany('App\Models\Service', 'proposal_service', 'proposal_id', 'service_id');
    }

    /**
     * Ritorna l'associazione con l'agente
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('App\Models\Agent')->withTrashed();
    }

    /**
     * Ritorna l'associazione con il customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    /**
     * Ritorna l'associazione con la quotation (se esistente)
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quotation()
    {
        return $this->hasOne('App\Models\Quotation');
    }

    /**
     * Codifico il valore in formato json per poi salvarlo nel DB
     * @param $value
     */
    public function setCarAccessoriesAttribute($value)
    {
        $this->attributes['car_accessories'] = !empty($value) ? json_encode($value) : null;
    }

    /**
     * Ritorno il campo car_accesories con i valori decodificati
     * @param $value
     * @return mixed
     */
    public function getCarAccessoriesAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Crea una proposal a partire da una nuova richiesta
     * @param Request $request
     * @param Customer $customer
     * @param Agent $agent
     * @return Proposal
     * @throws \Throwable
     */
    public static function createFromRequest(Request $request, Customer $customer, Agent $agent): Proposal
    {
        $offerCode = $request->input('offer.ref');

        /** @var Offer $offer */
        $offer = Offer::findOrFail($offerCode);

        if ($offer->trashed()) {
            throw new \Exception('Offer not valid');
        }

        $monthlyRate = $request->input('newMonthlyRate', $offer->monthly_rate);

        $proposal = new self();
        $proposal->offer_id = $offer->id;
        $proposal->agent_id = $agent->id;
        $proposal->customer_id = $customer->id;
        $proposal->deposit = $request->input('offer.deposit', $offer->deposit);
        $proposal->monthly_rate = intval($monthlyRate);
        $proposal->duration = $request->input('offer.duration', $offer->duration);
        $proposal->distance = $request->input('offer.distance', $offer->distance);
        // @deprecated
        $proposal->change_tires = $request->get('cambio-pneumatici', false);
        // @deprecated
        $proposal->car_replacement = $request->get('vettura-sostitutiva', false);
        // @unused
        $proposal->copertura_assicurativa = null;
        // @deprecated
        $proposal->pai = $request->get('pai', null);
        // @deprecated
        $proposal->tutela_legale = $request->get('tutela-legale', null);
        // @deprecated
        $proposal->assistenza_stradale = $request->get('assistenza-stradale', null);
        $proposal->notes = $request->notes;

        if (!$offer->isCustom()) {
            $proposal->franchise_insurance = $request->get('franchise_insurance', "10 %");
            $proposal->franchise_kasko = $request->get('franchise_kasko', "500 €");
        }

        //get accessories
        $accessoriesList = [];

        //car accessories
        $accessories = $request->get('accessories', []);
        foreach ($accessories as $accessory) {
            $accessoriesList[] = CarAccessory::getDescriptionFromCode($accessory);
        }

        //car colors
        $colors = $request->get('colors', []);
        foreach ($colors as $color) {
            $accessoriesList[] = "Colore: " . CarAccessory::getDescriptionFromCode($color);
        }

        if (!empty($accessoriesList)) {
            $proposal->car_accessories = $accessoriesList;
        }

        $proposal->saveOrFail();

        self::setServicesFromRequest($request, $proposal);

        event(new ProposalCreated($proposal));

        return $proposal;
    }

    /**
     * Salva i servizi aggiuntivi della proposal
     * è retrocompatibile con la vecchia gestione dei servizi
     * @param Request $request
     * @param Proposal $proposal
     */
    public static function setServicesFromRequest(Request $request, Proposal $proposal)
    {
        $groupServices = $proposal->agent->myGroup->services()->get();

        if (!empty($groupServices) && !empty($request->get('additionalServices'))) {
            foreach ($request->get('additionalServices') as $additionalService) {
                $service = $groupServices->firstWhere('slug', $additionalService['slug']);

                if ($service) {
                    $proposal->attachService($service->id);
                }
            }
        }
    }

    /**
     * Attach one service to the proposal
     * @param int $serviceId
     * @return mixed
     */
    public function attachService($serviceId)
    {
        if (\DB::table('proposal_service')->where([['service_id', $serviceId], [ 'proposal_id', $this->id]])->exists()) {
            return true;
        }

        $timestamp = Carbon::now()->toDateTimeString();

        return  \DB::table('proposal_service')->insert([
            'service_id' => $serviceId,
            'proposal_id' => $this->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }

    /**
     * Remove one service to the proposal
     * @param int $serviceId
     * @return mixed
     */
    public function detachService($serviceId)
    {
        if (!\DB::table('proposal_service')->where([['service_id', $serviceId], [ 'proposal_id', $this->id]])->exists()) {
            return true;
        }

        return  \DB::table('proposal_service')->where([
            'service_id' => $serviceId,
            'proposal_id' => $this->id
        ])->delete();
    }

    /**
     * Formatta la data di creazione
     * @return string
     */
    public function getHumanDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-M-Y');
    }

    /**
     * Incrementa il valore del print
     */
    public function incrementPrintCounter(): void
    {
        $lastPrintAt = Carbon::now();
        $this->increment('print_count', 1, ['last_print_at' => $lastPrintAt]);
    }
}
