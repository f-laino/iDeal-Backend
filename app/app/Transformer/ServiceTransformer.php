<?php

namespace App\Transformer;

use App\Models\Service;

/**
 * @OA\Schema(
 *  schema="Service",
 *  type="object",
 *  @OA\Property(property="slug", type="string", example="assicurazione-rca-kasko"),
 *  @OA\Property(property="name", type="string", example="Assicurazione"),
 *  @OA\Property(property="description", type="string", example="Assicurazione RCA e KASKO"),
 *  @OA\Property(property="included", type="boolean"),
 *  @OA\Property(property="icon", type="string", example="https://cdn1.carplanner.com/icons/services/assicurazione-rca.svg"),
 *  @OA\Property(property="order", type="integer", example="1"),
 * )
 */
class ServiceTransformer extends BaseTransformer
{
    public function transform(Service $service)
    {
        return [
            'slug' => (string)$service->slug,
            'name' => (string)$service->name,
            'description' => (string)$service->description,
            'included' => (boolean)$service->included,
            'icon' => $service->icon,
            'order' => $service->order
        ];
    }
}
