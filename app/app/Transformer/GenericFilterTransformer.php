<?php

namespace App\Transformer;

use App\Common\Models\GenericFilter;

class GenericFilterTransformer extends BaseTransformer
{
    protected $defaultIncludes = [];

    protected $availableIncludes = [];

    /**
     * @param GenericFilter $filter
     * @return array
     */
    public function transform(GenericFilter $filter)
    {
        return [
            'value' => $filter->getValue(),
            'label' => $filter->getLabel(),
            'name' =>  $filter->getName(),
        ];
    }
}
