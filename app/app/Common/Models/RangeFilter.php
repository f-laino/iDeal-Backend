<?php

namespace App\Common\Models;

/**
 * Class RangeFilter
 * @package App\Common\Models
 */
class RangeFilter
{
    private $min = 0;
    private $max = 0;
    private $step = 1;
    private $unit = '';

    /**
     * RangeFilter constructor.
     * @param int|float $min
     * @param int|float $max
     * @param int|float $step
     * @param string $unit
     */
    public function __construct($min, $max, $step, $unit = '')
    {
        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
        $this->unit = $unit;
    }

    public function getData()
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'unit' => $this->unit,
        ];
    }
}
