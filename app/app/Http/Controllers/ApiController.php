<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class ApiController
 * @package App\Http\Controllers
 * @property integer $statusCode
 * @property integer $pagination
 *
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="iDEAL private Api"
 *     ),
 *     @OA\Server(
 *         url="http://127.0.0.1:8000/api/v1"
 *     ),
 *     @OA\Server(
 *         url="https://deals-staging.ideal-rent.com/api/v1"
 *     ),
 *     security={{"JwtAuth": {}}},
 * )
 * @OA\SecurityScheme(securityScheme="JwtAuth", type="http", in="header", scheme="bearer", bearerFormat="JWT")
 * @OA\Schema(
 *  schema="Error500",
 *  description="Internal server error"
 * )
 */

class ApiController extends Controller
{
    protected $statusCode = 200;
    protected static $pagination = 20;

    const CODE_SUCCESS          = 'SUCCESS';
    const CODE_WRONG_ARGS       = 'WRONG_ARGS';
    const CODE_NOT_FOUND        = 'NOT_FOUND';
    const CODE_INTERNAL_ERROR   = 'INTERNAL_ERROR';
    const CODE_UNAUTHORIZED     = 'UNAUTHORIZED';
    const CODE_FORBIDDEN        = 'FORBIDDEN';
    const CODE_TOKEN_BLACKLISTED    = 'TOKEN_BLACKLISTED';
    const CODE_TOKEN_EXPIRED    = 'TOKEN_EXPIRED';

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;

        if (Input::has('include') && !empty(Input::get('include')))
        {
            $this->fractal->parseIncludes(Input::get('include'));
        }

       $this->middleware('jwt.verify', ['except' => ['login', 'activate', 'forgot', 'reset']]);
    }

    /**
     * Getter for statusCode
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $item
     * @param $callback
     * @return mixed
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param $collection
     * @param $callback
     * @param bool $withoutKey
     * @return mixed
     */
    protected function respondWithCollection($collection, $callback, $withoutKey = false)
    {
        $resource = new Collection($collection, $callback);

        $rootScope = $this->fractal->createData($resource);
        if($withoutKey){
            $data = $rootScope->toArray();
            if(empty($data))
                return $this->respondWithArray([]);
            return $this->respondWithArray(reset($data));
        }

        return $this->respondWithArray($rootScope->toArray());
    }


    /**
     * @param $collection
     * @param $callback
     * @return mixed
     */
    protected function respondWithPaginatedCollection($collection, $callback){
        $resource = new Collection($collection, $callback);
        $rootScope = $this->fractal->createData($resource);
        return $this->respondWithArray(
            [
                'total' => $collection->total(),
                'lastPage' => $collection->lastPage(),
                'currentPage' => $collection->currentPage(),
                'perPage' => $collection->perPage(),
                'items' => $rootScope->toArray(),
            ]
        );
    }

    /**
     * @param $collection
     * @param $callback
     * @param CursorInterface $cursor
     * @return mixed
     */
    protected function respondWithCursor($collection, $callback, CursorInterface $cursor)
    {
        $resource = new Collection($collection, $callback);
        $resource->setCursor($cursor);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * @param array $array
     * @param array $headers
     * @return mixed
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        $statusCode = $this->statusCode;
        if(isset($array['data']['statusCode']))
            $statusCode = $array['data']['statusCode'];

        return Response::json($array, $statusCode, $headers);
    }

    /**
     * @param array $response
     * @param string $message
     * @return mixed|Response
     */
    protected function respondWithSuccess($response = [], $message = 'Response has been generated successfully.')
    {
        if ($this->statusCode !== 200) {
            trigger_error(
                "Error in the message, success message on a http status not equal to 200...",
                E_USER_WARNING
            );

            return $this->errorInternalError('Internal Error.');
        }

        return $this->respondWithArray([
            'data' => [
                'http_code' => $this->statusCode,
                'data' => $response,
                'message' => $message,
            ]
        ]);
    }

    /**
     * @param $message
     * @param $errorCode
     * @return mixed
     */
    protected function respondWithError($message, $errorCode)
    {
        $this->setErrorHeader();

        if ($this->statusCode === 200) {
            trigger_error(
                "Error in the 'erroring', error message on a http status code 200...",
                E_USER_WARNING
            );
            $this->statusCode = 500;
        }

        return $this->respondWithArray([
            'error' => [
                'code' => $errorCode,
                'http_code' => $this->statusCode,
                'message' => $message,
            ]
        ]);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorForbidden($message = 'Forbidden')
    {
        $this->setErrorHeader();
        return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorInternalError($message = 'Internal Error')
    {
        $this->setErrorHeader();
        return $this->setStatusCode(500)->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorNotFound($message = 'Resource Not Found')
    {
        $this->setErrorHeader();
        return $this->setStatusCode(404)->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->setErrorHeader();
        return $this->setStatusCode(401)->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorExpiredToken($message = 'Authorization Token has expired.')
    {
        $this->setErrorHeader();
        return $this->setStatusCode(401)->respondWithError($message, self::CODE_TOKEN_EXPIRED);
    }
    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorTokenBlackListed($message = 'Authorization Token has been blacklisted.')
    {
        $this->setErrorHeader();
        return $this->setStatusCode(401)->respondWithError($message, self::CODE_TOKEN_BLACKLISTED);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * Generates a Response with a 200 HTTP header and a given message.
     *
     * @param string $message
     * @return Response
     */
    public function sendSuccess($message = 'Request process successfully.')
    {
        return $this->setStatusCode(200)->respondWithSuccess($message, self::CODE_SUCCESS);
    }

    /**
     * Prepare Cursor Logic for Response, related to entity repository
     *
     * @param Repository $repository
     * @param $cursor
     * @param $limit
     * @param null $filter
     * @return array
     */
    public function prepareCursorResponse($repository, $cursor, $limit, $filter = null)
    {
        $currentCursor = (int) $cursor;
        $limit = (int) $limit;
        $prevCursor = ($currentCursor - $limit > 0) ? ($currentCursor - $limit) : 0;

        list($collection, $count) = $repository->cursor($currentCursor, $limit, $filter);

        if ( ! $count) {
            $newCursor = 0;
        } else {
            $newCursor = $currentCursor+1;
        }

        $cursor = new Cursor($currentCursor, $prevCursor, $newCursor, $count);
        return array($collection, $cursor);
    }

    public function getRequestParams(Request $request){
        return json_decode($request->getContent(), true);
    }

    public function setErrorHeader()
    {
//        header("Access-Control-Allow-Origin: *");
    }


    public function getDates($interval = 'today'){
        $current = NULL;
        $previous = NULL;

        switch ($interval){
            case 'days':
                $current = Carbon::today()->subDays(7)->toDateTimeString();
                $previous = Carbon::today()->subDays(14)->toDateTimeString();
                break;
            case 'month':
                $current = Carbon::today()->subDays(30)->toDateTimeString();
                $previous = Carbon::today()->subDays(60)->toDateTimeString();
                break;
            case 'months':
                $current = Carbon::today()->subDays(30)->toDateTimeString();
                $previous = Carbon::today()->subDays(60)->toDateTimeString();
                break;
            case 'year':
                $current = Carbon::today()->firstOfYear()->toDateTimeString();
                $previous = Carbon::today()->subYear(1)->firstOfYear()->toDateTimeString();
                break;
            default:
                $current = Carbon::today();
                $previous = Carbon::yesterday();
        }
        return [$current, $previous];
    }

}
