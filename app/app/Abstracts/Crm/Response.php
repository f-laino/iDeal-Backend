<?php

namespace App\Abstracts\Crm;

abstract class Response implements \App\Interfaces\Crm\Response
{
    /**
     * The response code.
     *
     * @var integer
     */
    protected $statusCode;

    /**
     * The response data.
     *
     * @var mixed
     */
    protected $content;

    /**
     * The response headers.
     *
     * @var array
     */
    private $headers;

    /**
     * Response constructor.
     *
     * @param       $statusCode
     * @param       $content
     * @param array $headers
     */
    public function __construct($statusCode, $content, $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
        $this->headers = $headers;
    }

    /**
     * @inheritDoc
     */
    public function isSuccess(): bool
    {
        if ( !$this->getContent() ) {
            return false;
        }

        return $this->getContent()->success;
    }

    /**
     * @inheritDoc
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @inheritDoc
     */
   public function getStatusCode(): int
   {
       return $this->statusCode;
   }

    /**
     * @inheritDoc
     */
   public function getHeaders(): array
   {
       return $this->headers;
   }

    /**
     * @inheritDoc
     */
   public function getData()
   {
       if ($this->isSuccess() && isset($this->getContent()->data)) {
           return $this->getContent()->data;
       }

       return null;
   }

}
