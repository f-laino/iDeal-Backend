<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Fuel
 * @package App
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property string|null $description
 *
 * @OA\Schema(
 *  schema="FuelEntity",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="slug", type="string", example="benzina"),
 *  @OA\Property(property="name", type="string", example="Benzina"),
 *  @OA\Property(property="description", type="string"),
 * )
 */
class Fuel extends Model
{
    protected $table = 'fuels';
    public $timestamps = false;

    private static $petrol = ['B'];
    private static $gasoline = ['D'];
    private static $gpl = ['G'];
    private static $gas = ['M'];
    private static $e = ['E'];
    private static $hybrid = ['IB', 'ID', 'I'];


    /**
     * Cerca un'alimentazione partendo dal segmento (usato per memorizzare allestimenti tramite webservice)
     * @see Car::storeFormWebservice()
     * @param string $code
     *
     * @return Fuel
     */
    public static function getFromCode($code = '')
    {
        if (in_array($code, self::$gasoline)) {
            return self::where('slug', 'diesel')->first();
        } elseif (in_array($code, self::$gpl)) {
            return self::where('slug', 'gpl')->first();
        } elseif (in_array($code, self::$gas)) {
            return self::where('slug', 'metano')->first();
        } elseif (in_array($code, self::$hybrid)) {
            return self::where('slug', 'ibrida')->first();
        } elseif (in_array($code, self::$e)) {
            return self::where('slug', 'elettrica')->first();
        } else {
            return self::where('slug', 'benzina')->first();
        }
    }

    /**
     * Cerca un'alimantazione usando lo slug
     * @param $slug
     * @return mixed
     */
    public static function findBySlug($slug)
    {
        $slug = strtolower($slug);
        return self::where('slug', $slug)->firstOrFail();
    }

    /**
     * Get all available fuels as key => value collection
     * @return \Illuminate\Support\Collection
     */
    public static function asOptions()
    {
        return self::all()->pluck('name', 'slug');
    }
}
