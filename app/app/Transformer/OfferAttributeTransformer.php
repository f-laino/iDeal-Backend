<?php

namespace App\Transformer;

use App\Models\OfferAttributes;

class OfferAttributeTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];


    /**
     * @param OfferAttributes $item
     * @return array
     */
    public function transform(OfferAttributes $item = null)
    {
        return [
            'value' => (string) !empty($item) ? $item->value : "",
            'description' => (string) !empty($item) ? $item->description : "",
        ];
    }
}
