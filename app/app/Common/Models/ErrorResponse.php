<?php


namespace App\Common\Models;

use Illuminate\Support\Facades\Response;
/**
 * Class ErrorResponse
 * @package App\Common\Models
 */
class ErrorResponse
{

    private $statusCode;
    private $errors;
    private $message;

    public function __construct(int $statusCode, array $errors = [], string $message)
    {
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        $this->message = $message;
    }

    public function toJson(){
        $response = [
            'statusCode' => $this->statusCode,
        ];

        if(!empty($this->errors))
            $response['errors'] = $this->errors;

        if( !is_null($this->message) )
            $response['message'] = $this->message;

        return Response::json($response);
    }
}
