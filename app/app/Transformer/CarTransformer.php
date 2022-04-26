<?php
namespace App\Transformer;

use App\Models\Car;

/**
 * @OA\Schema(
 *  schema="Car",
 *  allOf={
 *      @OA\Schema(ref="#/components/schemas/CarEntity"),
 *      @OA\Schema(type="object",
 *          @OA\Property(property="brand", type="object",
 *          ref="#/components/schemas/Brand"
 *          )
 *      )
 *  }
 * )
 */
class CarTransformer extends BaseTransformer
{
    protected array $availableIncludes = ['category', 'fuel', 'images'];
    protected array $defaultIncludes = ['brand'];

    /**
     * @param Car $car
     * @return mixed
     */
    public function transform(Car $car)
    {
        $array = $car->attributesToArray();
        return $array;
    }

    public function includeBrand(Car $car)
    {
        return $this->item($car->brand, new BrandTransformer);
    }
    public function includeCategory(Car $car)
    {
        return $this->item($car->category, new CarCategoryTransformer);
    }

    public function includeFuel(Car $car)
    {
        return $this->item($car->fuel, new FuelTransformer);
    }

    public function includeImages(Car $car)
    {
        return $this->collection($car->images, new ImageTransformer);
    }
}
