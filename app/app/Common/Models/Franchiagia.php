<?php


namespace App\Common\Models;


class Franchiagia
{

    private $name;
    private $value;
    private $unit;

    public function __construct(string $name, $value, string $unit = '€')
    {
        $this->name = $name;
        $this->value = $value;
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function _toString(){
        return number_format($this->value, 0, '.', '.') . $this->unit;
    }

}
