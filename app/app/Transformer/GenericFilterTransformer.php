<?php

namespace App\Transformer;

use App\Common\Models\GenericFilter;
use App\Transformer\BaseTransformer;

class GenericFilterTransformer extends BaseTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

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
