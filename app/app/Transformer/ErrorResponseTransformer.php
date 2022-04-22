<?php

namespace App\Transformer;

use App\Common\Models\ErrorLogger;

class ErrorResponseTransformer extends BaseTransformer
{
    public function transform(\Exception $exception)
    {
        ErrorLogger::log($exception, 'ErrorResponseTransformer', $this, 'HTTP_RESPONSE');
        return [
            'statusCode' => 500,
            'errors' => $exception->getMessage(),
        ];
    }
}
