<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CarCategory
 * @package App
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 *
 * @OA\Schema(
 *  schema="CarCategoryEntity",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="slug", type="string", example="berlina"),
 *  @OA\Property(property="name", type="string", example="Berlina"),
 *  @OA\Property(property="description", type="string"),
 * )
 */
class CarCategory extends Model
{
    public $table = "car_categories";

    public $timestamps = false;
    public static $rules = [];

    private static $cityCars = ['A', 'B'];
    private static $berlina = ['D', 'E', 'G'];
    private static $sport = ['H', 'J'];
    private static $suv = ['I', 'R', 'S', 'T', 'U'];
    private static $monovolue = ['L', 'M', 'N', 'P', 'Q'];

    /**
     * Ritorna gli allestimenti associati a questa categoria
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    /**
     * Cerca una categoria partendo dal segmento (usato per memorizzare allestimenti tramite webservice)
     * @see Car::storeFormWebservice()
     * @param string|null $code
     * @return CarCategory
     */
    public static function getFromCode($code = '')
    {
        if (in_array($code, self::$cityCars)) {
            return self::where('slug', 'city-car')->first();
        } elseif (in_array($code, self::$berlina)) {
            return self::where('slug', 'berlina')->first();
        } elseif (in_array($code, self::$sport)) {
            return self::where('slug', 'sportiva-coupe')->first();
        } elseif (in_array($code, self::$suv)) {
            return self::where('slug', 'suv-crossover')->first();
        } elseif (in_array($code, self::$monovolue)) {
            return self::where('slug', 'monovolume')->first();
        } else {
            return self::where('slug', 'station-wagon')->first();
        }
    }

    /**
     * Cerca una categoria usando lo slug
     * @param string $slug
     * @return mixed
     */
    public static function findBySlug($slug)
    {
        $slug = strtolower($slug);
        return self::where('slug', $slug)->firstOrFail();
    }

    /**
     * Get all car categories as key => value collection
     * @return \Illuminate\Support\Collection
     */
    public static function asOptions()
    {
        return self::all()->pluck('name', 'slug');
    }
}
