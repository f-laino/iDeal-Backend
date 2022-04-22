<?php
namespace App\Common\Models;
use Log;

class ErrorLogger
{

    /**
     * Formatta il log prima della scrittura
     * @param \Exception $exception
     * @param string $label
     * @param $entity
     * @param string $entityType
     * @param string|null $channel
     * @param string $level
     */
    public static function log(\Exception $exception, string $label, $entity, string $entityType, string $channel = NULL, string $level = 'error')
    {
        $body = [
            'entity' => $entity,
            'entityType' => $entityType,
            'errorCode' => $exception->getCode(),
            'errorMessage' => $exception->getMessage(),
            'errorTrace' => $exception->getTrace(),
        ];

        if(!empty($channel)){
            Log::channel('pipedrive')->{$level}($label, $body);
        } else {
            Log::{$level}($label, $body);
        }

    }
}
