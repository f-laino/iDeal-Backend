<?php

namespace App\Http\Controllers\Cdk;

use App\Interfaces\CdkServiceInterface;
use App\Http\Controllers\Controller as DefaultController;

/**
 * Class Controller
 * @package App\Http\Controllers\Cdk
 */
class Controller extends DefaultController
{

    /**
     * @var CdkService
     */
    public $service;

    public static $_CONTRACT_CODE = "env1";
    public static $_BUSINESS_UNIT = "comp";

    public function __construct(CdkServiceInterface $service)
    {
        $this->service = $service;
    }
}
