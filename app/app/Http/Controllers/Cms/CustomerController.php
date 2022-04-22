<?php

namespace App\Http\Controllers\Cms;

use App\Models\Customer;
use App\Http\Controllers\CmsController;
use Illuminate\Http\Request;

class CustomerController extends CmsController
{
    public function index()
    {
        $customers = Customer::paginate(self::$pagination);
        return view('customer.index', compact('customers'));
    }
}
