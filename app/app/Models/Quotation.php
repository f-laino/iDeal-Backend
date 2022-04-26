<?php

namespace App\Models;

use App\Common\Models\DocumentList as MandatoryDocumentList;
use App\Models\DocumentList;
use App\Events\QuotationCreated;
use App\Models\Proposal;
use Carbon\Carbon;
use Devio\Pipedrive\PipedriveFacade as Pipedrive;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Log;

/**
 * Class Quotation
 * @package App *
 * @property integer $id
 * @property integer $proposal_id
 * @property integer $stage
 * @property integer|null $crm_id
 * @property boolean $upload_documents
 * @property string $status
 * @property string|null $last_qualified_step
 * @property boolean $qualified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Quotation extends Model
{
    use Notifiable;

    protected $table = 'quotations';
    protected $guarded = [];

    public static $STATUS = [
        "OPEN" => "OPEN",
        "LOST" => "LOST",
        "WON" => "WON",
    ];

    /**
     * Ritorna l'associazione con la proposal
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposal()
    {
        return $this->belongsTo('App\Models\Proposal')->withTrashed();
    }

    /**
     * Ritorna l'elenco di file associati a questo preventivo
     * @return array
     */
    public function getAttachments(): array
    {
        $attached = Attachment::orWhere(function ($query) {
            $query->where([
                ['entity_id', $this->id],
                ['type', Attachment::TYPE_QUOTATION],
            ]);
        })
        ->orWhere(function ($query) {
            $query->where([
                ['entity_id', $this->proposal->customer->id],
                ['type', Attachment::TYPE_CUSTOMER],
            ]);
        })
        ->get()
        ->toArray();

        /** @var Collection $mandatories */
        $mandatories = MandatoryDocumentList::getByContractualCategoryAndBroker(
            $this->proposal->customer->contractual_category_id,
            $this->proposal->offer->broker
        );

        $result = [];

        foreach ($mandatories as $mandatory) {
            $attachedExists = array_keys(array_column($attached, 'description'), $mandatory->type);

            $item = [
                'description' => $mandatory->type,
                'title' => $mandatory->title,
                'link' => $mandatory->link,
                'files' => [],
            ];

            foreach ($attachedExists as $attachedFileIndex) {
                $attachment = new Attachment();
                $attachment->description = $mandatory->type;
                $attachment->id = $attached[$attachedFileIndex]['id'];
                $attachment->filename = $attached[$attachedFileIndex]['name'];
                $attachment->title = $mandatory->title;
                $attachment->link = $mandatory->link;

                $item['files'][] = $attachment;
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Ritorna l'elenco dei documenti richiesti da un deal
     * @return Collection
     */
    public function getMandatoryDocuments()
    {

        /** @var Customer $customer */
        //da fixare non ho capito il perche non legge i dati dalla relationship
        //probabilmente perche manca la chiave in tabella
        $customer = Customer::find($this->proposal->customer_id);

        $category = $customer->category;
        $needed = MandatoryDocumentList::where([
            'contractual_category_id' => $category->id,
            'broker' => $this->proposal->offer->broker
        ]);
        return $needed->with('document')->get();
    }

    /**
     * Ritorna l'elenco dei file mancanti
     * @return array
     */
    public function getMissingDocuments()
    {
        $quotationFiles = $this->countAttachmentsByType()->toArray();
        $needed = $this->getMandatoryDocuments()->toArray();
        $attachedTypes = array_column($quotationFiles, 'description');
        $missingDocuments = [];

        foreach ($needed as $neededDocument) {
            if (!in_array($neededDocument['document']['type'], $attachedTypes)) {
                $missingDocuments[] = $neededDocument;
            }
        }

        return $missingDocuments;
    }

    /**
     * Controlla se l'utente ha tutti i documenti neccessari per il deal
     * @return bool
     */
    public function hasMandatoryDocuments() : bool
    {
        $missing = $this->getMissingDocuments();
        return count($missing) === 0;
    }

    /**
     * Ritorna il totale dei documenti caricati raggruppati per tipologia
     * @return Collection
     */
    public function countAttachmentsByType()
    {
        return Attachment::orWhere(function ($query) {
            $query->where([
                ['entity_id', $this->id],
                ['type', Attachment::TYPE_QUOTATION],
            ]);
        })
        ->orWhere(function ($query) {
            $query->where([
                ['entity_id', $this->proposal->customer->id],
                ['type', Attachment::TYPE_CUSTOMER],
            ]);
        })
        ->groupBy('description')
        ->get(['description', \DB::raw('type,  COUNT(*) AS items')]);
    }

    /**
     * Determina se la quotation possiede dei documenti associati tramite il customer
     * @return boolean
     */
    public function hasAttachments()
    {
        return Attachment::orWhere(function ($query) {
            $query->where([
                ['entity_id', $this->id],
                ['type', Attachment::TYPE_QUOTATION],
            ]);
        })
        ->orWhere(function ($query) {
            $query->where([
                ['entity_id', $this->proposal->customer->id],
                ['type', Attachment::TYPE_CUSTOMER],
            ]);
        })
        ->exists();
    }

    /**
     * Ritorna l'elenco delle offerte create nel giorno indicato
     * @param Carbon $day
     * @return Collection
     */
    public static function getTotalsByDay(Carbon $day)
    {
        return self::where('created_at', '>=', $day->format('Y-m-d'))
            ->where('created_at', '<=', $day->addDay()->format('Y-m-d'))
            ->get();
    }

    /**
     * Ritorna l'elenco delle offerte create nella settimana indicata
     * @param int $carbonWeek
     *
     * @return Collection
     */
    public static function getTotalsByWeek(int $carbonWeek)
    {
        $date = Carbon::now();
        $date->setISODate($date->year, $carbonWeek);
        return self::where('created_at', '>=', $date->startOfWeek()->format('Y-m-d'))
                    ->where('created_at', '<=', $date->endOfWeek()->format('Y-m-d'))
                    ->get();
    }

    /**
     * Ritorna l'elenco delle offerte create nel mese indicato
     * @param int $month
     *
     * @return Collection
     */
    public static function getTotalsByMonth(int $month)
    {
        return self::whereMonth('created_at', '=', $month)->get();
    }

    /**
     * Ritorna l'elenco delle offerte create nell'anno indicato
     * @param int $year
     *
     * @return Collection
     */
    public static function getTotalsByYear(int $year)
    {
        return self::whereYear('created_at', '=', $year)->get();
    }

    /**
     * Ritorna la stringa html contenente la data e l'ora di creazione
     * @return string
     */
    public function getHTMLDateAttribute()
    {
        $time = Carbon::parse($this->created_at)->format('H:i');
        $date = Carbon::parse($this->created_at)->format('d-F-Y');
        return "<span class=\"smaller lighter\">$time</span> $date";
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
     * @param string $agentType
     * @return float|int
     * @deprecated
     *
     */
    public function getCommission($agentType="agent")
    {
        $offer = $this->proposal->offer;
        try {
            $commissionIndexer = Fee::where('broker', $offer->broker)->firstOrFail();
        } catch (\Exception $exception) {
            \Log::alert("Quotation id:$this->id error. Offer indexer error offer_id:$offer->id error message: " . $exception->getMessage());
            return 0;
        }

        $pattern = json_decode($commissionIndexer->pattern, true);

        $segment = $offer->car->segmento;
        $priceType = 'monthly_rate';

        if (!empty($offer->web_monthly_rate) && $this->monthly_rate == $offer->web_monthly_rate) {
            $priceType = 'web_monthly_rate';
        }

        if (isset($pattern["$segment&$agentType&$priceType"])) {
            $value = $pattern["$segment&$agentType&$priceType"];
        } else {
            $value = 0;
        }

        return floatval($value);
    }

    /**
     * Ritorna l'indirizzo email di notifica del gruppo
     * @return string|null
     */
    public function getNotifyAddress()
    {
        $agent = $this->proposal->agent();
        $group = $agent->myGroup;
        if (!empty($group)) {
            return $group->notification_email;
        }
        return null;
    }

    /**
     * Ritorna l'id della quotation formattato
     * @return string
     */
    public function getNumber()
    {
        return str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Ritorna il fee dell'agente per questa quotation
     * @return float
     */
    public function getFee()
    {
        return $this->proposal->offer->fee($this->proposal->agent);
    }

    /**
     * Codifico il valore in formato json per poi salvarlo nel DB
     * @param $value
     */
    public function setCarAccessoriesAttribute($value)
    {
        $this->attributes['car_accessories'] = json_encode($value);
    }

    /**
     *  Ritorno il campo car_accesories con i valori decodificati
     * @param $value
     * @return mixed
     */
    public function getCarAccessoriesAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Crea una ogetto a partire da una nuova richiesta
     * @param Proposal $proposal
     * @param Offer $offer
     * @return Quotation
     * @throws \Throwable
     */
    public static function createFromProposal(Proposal $proposal, Offer $offer): Quotation
    {
        $quotation = new self();
        $quotation->stage = 1;
        $quotation->proposal_id = $proposal->id;

        $quotation->saveOrFail();

        event(new QuotationCreated($quotation));

        return $quotation;
    }
}
