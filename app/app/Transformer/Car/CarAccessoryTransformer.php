<?php
namespace App\Transformer\Car;

use App\Models\CarAccessory;
use App\Models\CarAccessoryGroup;
use App\Transformer\BaseTransformer;

/**
 * @OA\Schema(
 *  schema="CarAccessory",
 *  type="object",
 *  @OA\Property(property="price", type="integer", example="700"),
 *  @OA\Property(property="code", type="string", example="eyJpdiI6Imcrc3J6YmdZRlVCV3g0REZuYmpGelE9PSIsInZhbHVlIjoiSXNYdjFaWHJFem1KSUorYWRVWEFGZz09IiwibWFjIjoiMTZlYTA2MjkzNGNhZGFjYTI1Mjc2MTViZjAzZDVhN2Y5MDhkNzNiNjc4NDAyMzdmNmI2MDEwZGJlZjBlOGQxZSJ9"),
 *  @OA\Property(property="description", type="string", example="Nero vulcano"),
 *  @OA\Property(property="short_description", type="string", example=""),
 *  @OA\Property(property="standard_description", type="string", example="Vernice metallizzata"),
 *  @OA\Property(property="group", type="string", example="Vernici"),
 *  @OA\Property(property="group_code", type="string", example="0019"),
 * )
 */
class CarAccessoryTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param CarAccessory $accessory
     * @return array
     */
    public function transform(CarAccessory $accessory)
    {
        /** @var CarAccessoryGroup $group */
        $group = $accessory->group;

        return [
            'price' => $accessory->price,
            'code' => $accessory->code,
            'description' => $accessory->description,
            'short_description' => $accessory->short_description,
            'standard_description' => $accessory->standard_description,
            'group' => $group->description,
            'group_code' => $group->code,
        ];
    }
}
