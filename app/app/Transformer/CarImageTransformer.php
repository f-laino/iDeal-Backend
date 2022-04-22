<?php

namespace App\Transformer;

use App\Models\Image;

/**
 * @OA\Schema(
 *  schema="CarImage",
 *  type="object",
 *  @OA\Property(property="path", type="string", example="https://cdn1.carplanner.com/cars/Alfa Romeo/Giulietta/21ALF7011005M.jpg"),
 *  @OA\Property(property="code", type="string", example="gacr--10-hybrid-silver-ss-70cv"),
 *  @OA\Property(property="image_alt", type="string", example="lancia ypsilon-canva.jpg")
 * )
 */
class CarImageTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param CarImage $image
     * @return array
     */
    public function transform(Image $image)
    {
        return [
            'code' => (string)$image->code,
            'path' => (string)$image->path,
            'image_alt' => (string)$image->image_alt,
        ];
    }
}
