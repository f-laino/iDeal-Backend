<?php
namespace App\Transformer;

use App\Models\Carcategory;

/**
 * @OA\Schema(
 *  schema="CarCategory",
 *  type="object",
 *  @OA\Property(property="label", type="string", example="Berlina"),
 *  @OA\Property(property="name", type="string", example=""),
 *  @OA\Property(property="value", type="string", example="berlina")
 * )
 */
class CarCategoryTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param Carcategory $item
     * @return array
     */
    public function transform(Carcategory $item)
    {
        return [
            'value' => (string)$item->slug,
            'label' => (string)$item->name,
            'name' => (string)'',
        ];
    }
}
