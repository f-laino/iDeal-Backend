<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Promotion
 * @package App
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string|null $attachment_uri
 * @property boolean $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $expires_at
 */
class Promotion extends Model
{
    protected $table = 'promotions';
    public $timestamps = ['created_at', 'updated_at', 'expires_at'];
    protected $guarded = ['id'];

    public static $_TEMPLATES = [
        'promotion.attachments.welcome' => 'Prima comunicazione',
        'promotion.attachments.generic' => 'Offerta',
    ];

    /**
     * Ritorna una versione ridotta della description
     * @return string
     */
    public function getShortDescriptionAttribute()
    {
        return substr($this->description, 0, 40);
    }

    /**
     * Ritorna una versione ridotta del titolo
     * @return string
     */
    public function getShortTitleAttribute()
    {
        return substr($this->title, 0, 20);
    }

    /**
     * Associa un'offerta alla promozione
     * @param Offer $offer
     * @return bool
     */
    public function attachOffer(Offer $offer)
    {
        $exists = \DB::table('promotion_offer')
            ->where('offer_id', $offer->id)
            ->where('promotion_id', $this->id)
            ->exists();

        if ($exists) {
            return true;
        }
        $timestamp = Carbon::now()->toDateTimeString();

        $insert = \DB::table('promotion_offer')->insert([
            'offer_id' => $offer->id,
            'promotion_id' => $this->id,
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        //aggiorno la data di aggiornamento della promozione
        $this->touch();

        return $insert;
    }

    /**
     * Rimuove l'associazione fra un'offerta ed una promozione
     * @param Offer $offer
     * @return mixed
     */
    public function detachOffer(Offer $offer)
    {
        $remove = \DB::table('promotion_offer')
            ->where('offer_id', $offer->id)
            ->where('promotion_id', $this->id)
            ->delete();
        //aggiorno la data di aggiornamento della promozione
        $this->touch();
        return $remove;
    }

    /**
     * Rimuove le associazioni fra la promozione e tutte le sue offerte
     * @return mixed
     */
    public function detachOffers()
    {
        return \DB::table('promotion_offer')
            ->where('promotion_id', $this->id)
            ->delete();
    }

    /**
     * Ritorna l'elenco di offerte come lista
     * @param string|null $status indica lo stato dell'offerta
     * @return mixed
     */
    public static function getAsList($status = null)
    {
        $query = self::orderBy('id', 'ASC');
        if (!is_null($status)) {
            $query->where('status', $status);
        }
        return $query->get()->pluck('title', 'id');
    }

    /**
     * Ritorna l'elenco di offerte associate alla promozione
     * @param string|null $status indica lo stato delle offerte
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function offers($offerStatus = null)
    {
        $query = $this->belongsToMany('App\Models\Offer', 'promotion_offer');
        if (!is_null($offerStatus)) {
            $query->where('status', $this->status);
        }
        return $query;
    }

    /**
     * @return array
     */
    public static function getTemplates()
    {
        return array_keys(self::$_TEMPLATES);
    }

    /**
     * Ritorna l'elenco delle offerte
     * @param null $status indica lo stato della promozione
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByStatus($status = null)
    {
        $query = self::orderBy('id', 'ASC');
        if (!is_null($status)) {
            $query->where('status', $status);
        }
        return $query->get();
    }

    /**
     * Restituisce il titolo della promozione dato un agente
     * @param Agent $agent
     * @return string|string[]|null
     */
    public function getCompiledTitle(Agent $agent)
    {
        return $this->compileText($agent, $this->title);
    }

    /**
     * Restituisce la descrizione della promozione dato un agente
     * @param Agent $agent
     * @return string|string[]|null
     */
    public function getCompiledDescription(Agent $agent)
    {
        return $this->compileText($agent, $this->description);
    }

    /**
     * Sostituisce i placeholder presenti in un testo con i dati dell'agente
     * @param Agent $agent
     * @param string $text
     * @return string|string[]|null
     */
    private function compileText(Agent $agent, string $text)
    {
        //replace group type
        $groupTypePattern = '/##TIPOLOGIA##/';
        /** @var Group $group */
        $group = $agent->myGroup;

        $groupName = 'Il concessionario';
        if ($group->type == 'INSURANCE') {
            $groupName = "L'agenzia assicurativa";
        }

        $text = preg_replace($groupTypePattern, $groupName, $text);

        //replace agent name
        $agentNamePattern = '/##NOME##/';
        $name = $agent->getNomeCompleto();
        $text = preg_replace($agentNamePattern, $name, $text);

        $certificatoPattern = '/##CERTIFICATO##/';
        if ($group->type == 'INSURANCE') {
            $text = preg_replace($certificatoPattern, 'certificata', $text);
        } else {
            $text = preg_replace($certificatoPattern, 'certificato', $text);
        }

        $venditorePattern = '/##VENDITORE##/';
        if ($group->type == 'INSURANCE') {
            $text = preg_replace($venditorePattern, 'venditrice', $text);
        } else {
            $text = preg_replace($venditorePattern, 'venditore', $text);
        }

        return $text;
    }
}
