<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ContractualCategory
 * @package App
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property boolean $for_private
 * @property boolean $for_business
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @OA\Schema(
 *  schema="ContractualCategoryEntity",
 *  @OA\Property(property="code", type="string", example="tempo-indeterminato"),
 *  @OA\Property(property="description", type="string", example="Dipendente a tempo indeterminato"),
 *  @OA\Property(property="for_business", type="integer", example="0"),
 *  @OA\Property(property="for_private", type="integer", example="1"),
 *  @OA\Property(property="id", type="integer", example="1"),
 *  @OA\Property(property="created_at", type="string", example="2019-09-12 10:35:57"),
 *  @OA\Property(property="updated_at", type="string", example="2019-09-12 10:35:57"),
 * )
 */
class ContractualCategory extends Model
{
    public $table = "contractual_categories";

    public $timestamps = true;

    /**
     * @var int Id della categoria di default
     */
    public static $DEFAULT = 11;

    /**
     * Ottieni una categoria
     * @param string $code
     * @return ContractualCategory
     */
    public static function findOrFirst(string $code) : ContractualCategory
    {
        try {
            return self::where('code', $code)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return self::find(self::$DEFAULT);
        }
    }

    /**
     * Restituisce tutte le categorie valide
     * sovrascrive il metodo originale per filtrare quelle *-old
     *
     * @param  array|mixed  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all($columns = ['*'])
    {
        return (new self())
                    ->where('id', '>=', ContractualCategory::$DEFAULT)
                    ->get(
                        is_array($columns) ? $columns : func_get_args()
                    );
    }
}
