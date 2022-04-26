<?php

namespace App\Transformer;

use App\Models\Carcategory;
use App\Contractualcategory;

class ContractualcategoryTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param Carcategory $item
     * @return array
     */
    public function transform(Contractualcategory $item)
    {
        return [
            'id' => (integer)$item->id,
            'slug' => (string)$item->slug,
            'label' => (string)$item->label,
            'label_it' => (string)$item->label_it,
        ];
    }
}
