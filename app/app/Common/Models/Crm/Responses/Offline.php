<?php
namespace App\Common\Models\Crm\Responses;

use App\Abstracts\Crm\Response;

/**
 *
 */
class Offline extends Response
{
    public function __construct()
    {
        $statusCode = 200;
        $content = [];
        $headers = [];
        parent::__construct($statusCode, $content, $headers);
    }

    /**
     * @inheritDoc
     */
    function getEntityId()
    {
        return null;
    }
}
