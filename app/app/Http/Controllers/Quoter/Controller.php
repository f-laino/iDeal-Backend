<?php

namespace App\Http\Controllers\Quoter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as DefaultController;

class Controller extends DefaultController
{
    public function __construct()
    {
        $this->middleware('request.quoter');
    }
}
