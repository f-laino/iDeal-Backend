<?php

namespace App\Transformer;

use App\Models\Image;

/**
 * @OA\Schema(
 *  schema="Image",
 *  type="object",
 *  @OA\Property(property="source", type="string", example="https://cdn1.carplanner.com/cars/Alfa Romeo/Giulietta/21ALF7011005M.jpg"),
 *  @OA\Property(property="type", type="string", example="MAIN"),
 *  @OA\Property(property="description", type="string", example="Laterale Sinistra")
 * )
 */
class ImageTransformer extends BaseTransformer
{
    public function transform(Image $image)
    {
        return [
            'source' => (string)$image->path,
            'type' => (string)$image->type,
            'description' => (string)$image->image_alt,
        ];
    }
}
