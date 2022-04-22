<?php

namespace App\Transformer;

use App\Models\OfferAttributes;

class FilterTransformer extends BaseTransformer
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];


    /**
     * @param OfferAttributes $item
     * @return array
     */
    public function transform(OfferAttributes $item)
    {
        return [
            'value' => (string) !empty($item) ? $item->value : "",
            'label' => (string) !empty($item) ? $item->description : "",
            'name' => (string)'',
        ];
    }
}
