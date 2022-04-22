<?php
namespace App\Transformer\Car;

use App\Transformer\BaseTransformer;
use App\Models\Brand;

/**
 * @OA\Schema(
 *  schema="CarBrand",
 *  type="object",
 *  @OA\Property(property="name", type="string", example="Abarth"),
 *  @OA\Property(property="photo", type="string", example="https://cdn1.carplanner.com/cars/brands/abarth.svg"),
 *  @OA\Property(property="value", type="string", example="ABA")
 * )
 */
class BrandTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param Brand $item
     * @return array
     */
    public function transform(Brand $item)
    {
        return [
            'name' => (string)$item->name,
            'value' => (string)$item->slug,
            'photo' => (string)$item->logo,
        ];
    }
}
