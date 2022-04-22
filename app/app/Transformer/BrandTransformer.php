<?php

namespace App\Transformer;

use App\Models\Brand;

/**
 * @OA\Schema(
 *  schema="Brand",
 *  type="object",
 *  @OA\Property(property="icon", type="string", example="https://cdn1.carplanner.com/cars/brands/alfa-romeo.svg"),
 *  @OA\Property(property="icon_alt", type="string", example="Logo Alfa Romeo"),
 *  @OA\Property(property="label", type="string", example="Alfa Romeo"),
 *  @OA\Property(property="slug", type="string", example="ALF"),
 *  @OA\Property(property="value", type="string", example="Alfa Romeo")
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
            'value' => (string)$item->name,
            'slug' => (string)$item->slug,
            'label' => (string)$item->name,
            'icon' => (string)$item->logo,
            'icon_alt' => (string)$item->logo_alt,
        ];
    }
}
