<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class Service
 * @package App
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 * @property string $icon
 * @property bool included
 * @property integer price
 * @property integer $order
 */
class Service extends Model
{
    public $table = "services";

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * Cerca un sevice utilizzando il service-slug
     * @param string $slug
     * @throws ModelNotFoundException
     * @return mixed
     */
    public static function findBySlug(string $slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }

    /**
     * @param array $slugs
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findBySlugs(array $slugs)
    {
        return self::whereIn('slug', $slugs)->get();
    }

    /**
     * Ritorna l'elenco di servizi attivi di default
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDefaultServices()
    {
        return self::where('included', true)->get();
    }

    /**
     * Ritorna l'elenco di servizi a pagamento
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPaidServices()
    {
        return self::where('included', false)->get();
    }

    /**
     * Ritorna l'elenco di servizi a pagamento di default (non Ekly)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDefaultPaidServices()
    {
        return self::where('included', false)
                ->where(function($query) {
                    $query->where([['slug', 'cambio-pneumatici'], ['price', 15]]);
                })
                ->orWhere(function($query) {
                    $query->where([['slug', 'vettura-sostitutiva'], ['price', 25]]);
                })
                ->get();
    }
}
