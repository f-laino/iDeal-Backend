<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Brand
 * @package App
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string|null $title
 * @property string|null $description
 * @property string $logo
 * @property string|null $logo_alt
 *
 * @OA\Schema(
 *  schema="BrandEntity",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="slug", type="string", example="ABA"),
 *  @OA\Property(property="name", type="string", example="Abarth"),
 *  @OA\Property(property="title", type="string", example=""),
 *  @OA\Property(property="description", type="string", example=""),
 *  @OA\Property(property="logo", type="string", example="https://cdn1.carplanner.com/cars/brands/abarth.svg"),
 *  @OA\Property(property="logo_alt", type="string", example="Logo Abarth"),
 * )
 */
class Brand extends Model
{
    public $table = "brands";

    public $timestamps = false;

    protected $guarded = [];

    /**
     * Ritorna l'associazione con l'allestimento
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cars()
    {
        return $this->hasMany('App\Models\Car');
    }

    /**
     * Ritorna l'elenco dei brand che hanno automobili in un'offerta attiva
     * @param Agent $agent
     * @return Collection
     */
    public static function active(Agent $agent) : Collection
    {
        $car_ids = $agent->offers()->groupBy('car_id')->pluck('car_id');

        return self::whereHas('cars', function ($q) use ($car_ids) {
            $q->whereHas('offer', function ($offer) use ($car_ids) {
                $offer->where('status', true)->whereIn('car_id', $car_ids);
            });
        })->get();
    }

    /**
     * Cerca un brand usando lo slug
     * @param string $brandSlug
     * @return mixed
     */
    public static function findBySlug(string $brandSlug)
    {
        $brandSlug = strtoupper($brandSlug);
        return self::where('slug', $brandSlug)->firstOrFail();
    }
}
