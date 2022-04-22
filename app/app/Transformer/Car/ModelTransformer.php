<?php namespace App\Transformer\Car;

use App\Models\Car;
use App\Transformer\BaseTransformer;

/**
 * @OA\Schema(
 *  schema="CarModel",
 *  type="object",
 *  @OA\Property(property="name", type="string", example="GIULIETTA"),
 *  @OA\Property(property="value", type="string", example="2124")
 * )
 */
class ModelTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transform($model)
    {
        $name = Car::clearString($model->desc_gamma_mod);
        $name = strtoupper($name);
        return [
            'name' => (string)$name,
            'value' => (string)$model->cod_gamma_mod,
        ];
    }
}
