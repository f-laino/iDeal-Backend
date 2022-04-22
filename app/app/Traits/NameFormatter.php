<?php


namespace App\Traits;

/**
 * Trait NameFormat
 * @package App\Traits
 */
trait NameFormatter
{

    /**
     * @param string $value
     * @return string
     */
    protected function prettify(string $value): string
    {
        $value = strtolower($value);
        return ucwords($value);
    }

    /**
     * @param $value
     * @return string
     */
    protected static function formatString($value){
        $value = strtolower($value);
        return ucfirst($value);
    }

}
