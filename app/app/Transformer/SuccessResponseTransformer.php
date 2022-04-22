<?php

namespace App\Transformer;

class SuccessResponseTransformer extends BaseTransformer
{
    public function transform($response = [], $statusCode = 200)
    {
        $response['statusCode'] = $statusCode;
        return $response;
    }
}
