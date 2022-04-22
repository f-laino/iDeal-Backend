<?php

namespace App\Http\Controllers\Cms;

use App\Models\Customer;
use App\Http\Controllers\CmsController;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends CmsController
{
    public function index()
    {
        $quotations = Quotation::paginate(self::$pagination);
        return view('quotation.index', compact('quotations'));
    }
}
