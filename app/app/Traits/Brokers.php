<?php
namespace App\Traits;

/**
 *
 */
trait Brokers{

    public static $BROKERS = [
        'leasys' => 'Leasys',
        'arval' => 'Arval',
        'lease plan' => 'Lease Plan',
        'ald' => 'ALD',
        'alphabet' => 'Alphabet',
        'sifa' => 'Sifa',
        'ekly' => 'Ekly'
    ];

    /**
     * @return array
     */
    public static function getBrokerCodes(): array
    {
        return array_keys(self::$BROKERS);
    }

}
