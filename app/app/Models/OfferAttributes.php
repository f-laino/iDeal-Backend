<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OfferAttributes
 * @package App
 * @property integer $id
 * @property integer $offer_id
 * @property string $type
 * @property string $value
 * @property string|null $description
 */
class OfferAttributes extends Model
{
    public $timestamps = false;

    public static $FILTERS = ['LEFT_LABEL', 'RIGHT_LABEL'];

    protected $table = "offer_attributes";

    protected $guarded = [];

    public static $rentLabels = [
        "privati" => "Privati",
        "piva" => "P.IVA",
    ];

    /**
     * Perpare an array to be used as element for laravel Rules Validation
     * @param array $elements
     * @return string
     */
    public static function asRule(array $elements)
    {
        $elements = array_keys($elements);
        array_shift($elements);
        return implode(',', $elements);
    }

    /**
     * Get all active labels
     * @param Agent $agent
     * @return Collection
     */
    public static function activeLabels(): Collection
    {
        return self::where('type', 'LEFT_LABEL')
                ->whereIn('value', array_keys(self::$rentLabels))
                ->groupBy('value')
                ->get();
    }

    /**
     * Delete all offer attibute
     * @param Offer $offer
     * @return mixed
     */
    public static function deleteAllOfferAttributes(Offer $offer)
    {
        return self::where('offer_id', $offer->id)->delete();
    }

    /**
     * Delete offer attibute
     * @param Offer $offer
     * @return mixed
     */
    public static function deleteOfferAttribute(Offer $offer, string $type)
    {
        return self::where('offer_id', $offer->id)->where('type', $type)->delete();
    }
}
