<?php namespace App\Transformer\Car;

use App\Transformer\BaseTransformer;

/**
 * @OA\Schema(
 *  schema="CarModelVersion",
 *  type="object",
 *  @OA\Property(property="name", type="string", example="GIULIETTA 1.4 T. GIULIETTA 120CV MY19"),
 *  @OA\Property(property="value", type="string", example="ALF7011-1139195")
 * )
 */
class VersionTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transform($version)
    {
        $name = $version->Nome;
        $name = strtoupper($name);
        return [
            'name' => (string)$name,
            'value' => "$version->CodiceMotornet-$version->CodiceEurotax",
        ];
    }
}
