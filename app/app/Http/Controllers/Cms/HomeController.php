<?php

namespace App\Http\Controllers\Cms;
use App\Http\Controllers\CmsController;

class HomeController extends CmsController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route('offer.index');
    }


}
