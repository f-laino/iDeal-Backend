<?php

namespace App\Common\Models;

/**
 * Class GenericFilter
 * @package App\Common\Models
 */
class GenericFilter
{
    private $label = '';
    private $value = '';
    private $name = '';

    private $template = "{VALUE}";

    /**
     * GenericFilter constructor.
     * @param $value
     * @param null $label
     * @param string $name
     */
    public function __construct($value, $label = NULL, $name = '')
    {
        $this->setValue($value);
        $this->setLabel($value, $label);
        $this->name = $name;
    }

    /**
     * @param $value
     */
    public function setValue($value){
        $this->value = $value;
    }

    /**
     * @param $value
     * @param null $label
     * @return mixed|string|null
     */
    public function setLabel($value, $label = NULL){
        if( is_null($label) )
            return $this->label = $value;
        elseif ( intval($value) <= 0 && !is_null($label)){
            return $this->label = $label;
        }
        return $this->generateLabel($label, $value);
    }

   private function generateLabel($label, $value){
        if(strpos($label, $this->template) !== false){
            return $this->label = str_replace("{$this->template}", $value, $label);
        }
      // return $this->label = "$label $value";
       return $this->label = "$label";
   }


    /**
     * @return string
     */
    public function getLabel()
    {
        return (string)$this->label;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return (string)$this->value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->name;
    }

    public function _toArray(){
        return [
            'value' => $this->value,
            'label' => $this->label,
            'name' => $this->name,
        ];
    }
}
