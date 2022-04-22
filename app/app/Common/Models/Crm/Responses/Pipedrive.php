<?php
namespace App\Common\Models\Crm\Responses;

use App\Abstracts\Crm\Response;
use Devio\Pipedrive\Http\Response as PipedriveResponse;

/**
 * Class Pipedrive
 * @package App\Common\Models\Crm\Responses
 */
class Pipedrive extends Response
{
    /**
     * Pipedrive constructor.
     * @param PipedriveResponse $response
     */
    public function __construct(PipedriveResponse $response)
    {
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        $headers = $response->getHeaders();
        parent::__construct($statusCode, $content, $headers);
    }

    /**
     * @inheritDoc
     */
    function getEntityId()
    {
        return $this->getData()->id;
    }
}
