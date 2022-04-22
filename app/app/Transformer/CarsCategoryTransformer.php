<?php
namespace App\Transformer;

use App\Carcategory;

class CarsCategoryTransformer extends BaseTransformer
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
            'value' => (string)$item->name,
            'label' => (string)$item->name,
            'name' => (string)'',
        ];
    }
}
