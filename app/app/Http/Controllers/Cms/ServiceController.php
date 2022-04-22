<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\CmsController;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends CmsController
{
    public function index()
    {
        $services = Service::orderBy('order', 'asc')->paginate(self::$pagination);
        return view('service.index', compact('services'));
    }
}
