<?php

namespace App\Models;


class WebsiteCar extends Car
{
    protected $connection = "mysql2";

    public function brand()
    {
        return $this->belongsTo('App\WebsiteBrand');
    }

}
