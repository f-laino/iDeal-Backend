<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\OfferAttributes;
use App\Common\Models\CustomerService;
use App\Common\Models\Offers\Generic as GenericOffer;
use App\Services\Offers\OfferApiService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\OfferCreateRequest;
use App\Http\Requests\Api\OfferUpdateRequest;
use App\Http\Requests\Api\RequestNewOfferRequest;
use App\Notifications\Requests\NewOffer;
use App\Transformer\ErrorResponseTransformer;
use App\Transformer\OfferTransformer;
use App\Transformer\SuccessResponseTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Log;
use PHPUnit\Runner\Exception;

class OfferController extends ApiController
{
    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    /**
     * Show offer
     *
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Offer"},
     *   path="/offers/{code}",
     *   summary="get offer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/Offer"
     *       )
     *     )
     *   )
     * )
     */
    public function show(string $code)
    {
        try {
            /** @var Agent $agent */
            $agent = auth('api')->user();
            $offer = $agent->offers()->where("code", $code)->firstOrFail(); // getAgentOfferByCode($code, $agent)
            return $this->respondWithItem($offer, new OfferTransformer);
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
    }

    /**
     * Create new offer
     *
     * @param OfferCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers",
     *   summary="store new offer from catalog",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="car", type="integer"),
     *       @OA\Property(property="monthly_rate", type="integer"),
     *       @OA\Property(property="deposit", type="integer"),
     *       @OA\Property(property="distance", type="integer"),
     *       @OA\Property(property="duration", type="integer"),
     *       @OA\Property(property="notes", type="string"),
     *       @OA\Property(property="reference_code", type="string"),
     *       @OA\Property(property="rightLabel", type="object"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="code", type="string")
     *     )
     *   )
     * )
     */
    public function create(OfferCreateRequest $request, OfferApiService $offerService)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        $offer = $offerService->createFromRequest($request, $agent);

        $offerService->addLeftLabel($offer, 'catalogo', 'Catalogo');

        $offerService->attachAgentMembersToOffer($offer, $agent);

        $response = ['code' => $offer->code];

        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }

    /**
     * Update offer
     *
     * @param OfferUpdateRequest $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers/{code}",
     *   summary="update offer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="car", type="integer"),
     *       @OA\Property(property="deposit", type="integer"),
     *       @OA\Property(property="distance", type="integer"),
     *       @OA\Property(property="duration", type="integer"),
     *       @OA\Property(property="monthly_rate", type="integer"),
     *       @OA\Property(property="notes", type="string"),
     *       @OA\Property(property="reference_code", type="string"),
     *       @OA\Property(property="rightLabel", type="object"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="code", type="string")
     *     )
     *   )
     * )
     */
    public function update(OfferUpdateRequest $request, string $code, OfferApiService $offerService)
    {
        try {
            $offer = $offerService->updateFromRequest($request, $code);
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        $response = ['code' => $offer->code];

        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }

    /**
     * Store many offer children
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers/{code}/childs",
     *   summary="store child offer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *        @OA\Property(
     *          property="childs",
     *          type="object",
     *          @OA\Property(
     *              property="deposit-duration-distance",
     *              type="integer",
     *              description="monthly rate",
     *              example="2000-48-15000"
     *          )
     *        )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="code", type="string", description="parent offer code")
     *     )
     *   )
     * )
     */
    public function addChilds(Request $request, string $code)
    {
        /** @var Offer $offer */
        $offer = Offer::findByCode($code);

        try {
            $childs = $request->get('childs', []);
            $childs = $this->createChildsList($childs);
            $offer->deleteChilds();
            $offer->addChildOffers($childs);
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
        $response = ["code" => $offer->code];
        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }

    /**
     * Store one offer child
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers/{code}/child",
     *   summary="store child offer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *        @OA\Property(
     *          property="child",
     *          type="object",
     *          @OA\Property(
     *              property="deposit",
     *              type="integer",
     *              description="deposit",
     *              example="2000"
     *          ),
     *          @OA\Property(
     *              property="duration",
     *              type="integer",
     *              description="duration",
     *              example="48"
     *          ),
     *          @OA\Property(
     *              property="distance",
     *              type="integer",
     *              description="distance",
     *              example="15000"
     *          ),
     *          @OA\Property(
     *              property="monthlyRate",
     *              type="integer",
     *              description="monthlyRate",
     *              example="800"
     *          )
     *        )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="child", type="object", description="stored child")
     *     )
     *   )
     * )
     */
    public function addChild(Request $request, string $code)
    {
        /** @var Offer $offer */
        $offer = Offer::findByCode($code);

        try {
            $child = $offer->addChildOffer($request->duration, $request->distance, $request->deposit, $request->monthlyRate);
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
        $response = ["child" => $child];
        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }

    /**
     * Delete one offer child
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *   tags={"Offer"},
     *   path="/offers/{code}/child/idChild",
     *   summary="store child offer",
     *     @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Parameter(
     *     name="idChild",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   )
     * )
     */
    public function removeChild(string $code, string $idChild)
    {
        /** @var Offer $offer */
        $offer = Offer::findByCode($code);

        try {
            $offer->deleteChild($idChild);
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
        return $this->respondWithItem([], new SuccessResponseTransformer);
    }

    /**
     * Update offer status
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers/{code}/status",
     *   summary="update offer status",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *        @OA\Property(
     *          property="status",
     *          type="boolean"
     *        )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="code", type="string", description="parent offer code")
     *     )
     *   )
     * )
     */
    public function updateStatus(Request $request, string $code)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();
        /** @var Offer $offer */
        $offer = Offer::findByCode($code);
        $newStatus = $request->get('status', false);
        try {
            $offer->updateAgentOfferStatus($agent, $newStatus);
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
        $response = ["code" => $offer->code];
        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }

    /**
     * Add offer services
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers/{code}/services",
     *   summary="add offer services",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *        @OA\Property(
     *          property="services",
     *          type="array",
     *          @OA\Items()
     *        )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="code", type="string", description="parent offer code")
     *     )
     *   )
     * )
     */
    public function addServices(Request $request, string $code)
    {
        $slugs = $request->get('services', []);
        try {
            /** @var Offer $offer */
            $offer = Offer::findByCode($code);
            $offer->detachAllServices();
            $offer->attachServices($slugs);
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
        $response = ["code" => $offer->code];
        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }


    /**
     * Create a list with childs offer params
     *
     * @param array $childs
     *
     * @return array
     */
    private function createChildsList(array $childs)
    {
        $childOffers = [];
        foreach ($childs as $key => $monthlyRate) {
            list($deposit, $duration, $distance) = explode('-', $key);
            $child = [];
            $child['duration'] = $duration;
            $child['deposit'] = $deposit;
            $child['distance'] = $distance;
            $child['monthly_rate'] = $monthlyRate;
            $childOffers[] = $child;
        }
        return $childOffers;
    }

    /**
     * Request for new offer
     *
     * @param RequestNewOfferRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     *
     * @OA\Post(
     *   tags={"Offer"},
     *   path="/offers/request/new",
     *   summary="request for new offer",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"brand", "model"},
     *       @OA\Property(property="brand", type="string"),
     *       @OA\Property(property="model", type="string"),
     *       @OA\Property(property="version", type="string"),
     *       @OA\Property(property="monthly_rate", type="integer"),
     *       @OA\Property(property="duration", type="integer"),
     *       @OA\Property(property="deposit", type="integer"),
     *       @OA\Property(property="distance", type="integer"),
     *       @OA\Property(property="services", type="string"),
     *       @OA\Property(property="note", type="string"),
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function requestNewOffer(RequestNewOfferRequest $request)
    {

        /** @var Agent $agent */
        $agent = auth('api')->user();

        Log::info(
            'NEW CAR OFFER REQUEST',
            [
                'request' => $request->all(),
                'agent' => $agent
            ]
        );

        try {
            $customer = Customer::createFromRequest($request, $agent);
        } catch (\Exception $exception) {
            return $this->setStatusCode(500)->respondWithError($exception->getMessage(), 500);
        }


        $brand = $request->get('brand', '');
        $model = $request->get('model', '');
        $version = $request->get('version', '');
        $monthlyRate = $request->get('monthly_rate', 0);
        $duration = $request->get('duration', 0);
        $distance = $request->get('distance', 0);
        $deposit = $request->get('deposit', 0);
        $services = $request->get('services', '');
        $note = $request->get('notes', '');


        $offer = new GenericOffer(
            $brand,
            $model,
            $version,
            floatval($monthlyRate),
            intval($duration),
            intval($distance),
            intval($deposit),
            (string)$services,
            (string)$note
        );

        // invio email notifica customer service
        $customerService = new CustomerService(config('mail.support.address'));
        $mailNotification = new NewOffer($agent, $customer, $offer);
        $customerService->notify($mailNotification);

        //invio email notifica utente
        $agent->sendCustomOfferRequestedNotification($customer, $offer);

        return $this->respondWithSuccess();
    }
}
