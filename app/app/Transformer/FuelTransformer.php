<?php

namespace App\Transformer;

use App\Models\Fuel;

/**
 * @OA\Schema(
 *  schema="Fuel",
 *  type="object",
 *  @OA\Property(property="label", type="string", example="Benzina"),
 *  @OA\Property(property="name", type="string", example=""),
 *  @OA\Property(property="value", type="string", example="benzina")
 * )
 */
class FuelTransformer extends BaseTransformer
{
    public function transform(Fuel $item)
    {
        return [
            'value' => (string)$item->slug,
            'label' => (string)$item->name,
            'name' => (string)$item->description,
        ];
    }
}
