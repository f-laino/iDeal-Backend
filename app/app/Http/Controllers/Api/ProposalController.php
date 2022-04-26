<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Customer;
use App\Http\Controllers\ApiController;
use App\Models\Proposal;
use App\Models\Quotation;
use App\Services\PrintService;
use App\Transformer\ErrorResponseTransformer;
use App\Transformer\ProposalTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\Fractal\Manager;

class ProposalController extends ApiController
{
    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    /**
     * Memorizza una nuova proposal
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     *
     * @OA\Post(
     *   tags={"Proposal"},
     *   path="/proposals",
     *   summary="store new proposal",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"offer"},
     *       allOf={
     *          @OA\Schema(ref="#/components/schemas/CustomerForm"),
     *          @OA\Schema(@OA\Property(property="zip_code", type="string", example="00100")),
     *          @OA\Schema(type="object",
     *            @OA\Property(
     *              property="offer",
     *              type="object",
     *              @OA\Property(property="ref", type="integer"),
     *              @OA\Property(property="deposit", type="integer"),
     *              @OA\Property(property="duration", type="integer"),
     *              @OA\Property(property="distance", type="integer")
     *            ),
     *          ),
     *          @OA\Schema(@OA\Property(property="newMonthlyRate", type="integer")),
     *          @OA\Schema(@OA\Property(property="additionalServices", type="array", @OA\Items())),
     *          @OA\Schema(@OA\Property(property="franchise_insurance", type="string")),
     *          @OA\Schema(@OA\Property(property="franchise_kasko", type="string")),
     *          @OA\Schema(@OA\Property(property="accessories", type="array", @OA\Items())),
     *          @OA\Schema(@OA\Property(property="colors", type="array", @OA\Items())),
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/Proposal"
     *       )
     *     )
     *   ),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function store(Request $request)
    {
        Log::info('STORE PROPOSAL REQUEST', [ 'request' => $request->all() ]);

        /** @var Agent $agent */
        $agent = auth('api')->user();

        try {
            $customer = Customer::createFromRequest($request, $agent);
            $proposal = Proposal::createFromRequest($request, $customer, $agent);
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        return $this->respondWithItem($proposal, new ProposalTransformer);
    }

    /**
     * Aggiorna una proposal
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     *
     * @OA\Put(
     *   tags={"Proposal"},
     *   path="/proposals/{id}",
     *   summary="update a proposal",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"offer"},
     *       allOf={
     *          @OA\Schema(ref="#/components/schemas/CustomerForm"),
     *          @OA\Schema(@OA\Property(property="notes", type="string"))
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         ref="#/components/schemas/Proposal"
     *       )
     *     )
     *   ),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function update(Request $request, $id)
    {
        Log::info('UPDATE PROPOSAL REQUEST', [ 'request' => $request->all() ]);

        /** @var Agent $agent */
        $agent = auth('api')->user();

        try {
            /** @var Proposal $proposal */
            $proposal = Proposal::findOrFail($id);

            /** @var Offer $offer */
            $offer = Offer::findOrFail($proposal->offer_id);

            if ($offer->trashed()) {
                throw new \Exception('Offer not valid');
            }

            $customer = Customer::createFromRequest($request, $agent);

            $proposal->customer_id = $customer->id;
            $proposal->notes = $request->notes;
            $proposal->save();
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        return $this->respondWithItem($proposal, new ProposalTransformer);
    }

    /**
     * Get a proposal
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Proposal"},
     *   path="/proposals/{id}",
     *   summary="get a proposal",
     *   @OA\Parameter(
     *     name="id",
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
     *         ref="#/components/schemas/Proposal"
     *       )
     *     )
     *   ),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function show(int $id)
    {
        try {
            /** @var Proposal $proposal */
            $proposal = Proposal::where("id", $id)->firstOrFail();
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        return $this->respondWithItem($proposal, new ProposalTransformer);
    }

    /**
     * @param int $id
     * @param PrintService $printService
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Proposal"},
     *   path="/proposals/{id}/attachment",
     *   summary="download proposal pdf",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Header(header="Content-Disposition", @OA\Schema(type="string"), description="attachment; filename=preventivo-{timestamp}.pdf")
     *   )
     * )
     */
    public function attachment(int $id, PrintService $printService)
    {
        try {

            /** @var Proposal $proposal */
            $proposal = Proposal::findOrFail($id);

            if ($proposal->quotation()->exists()) {
                /** @var Quotation $quotation */
                $quotation = $proposal->quotation;
                return $printService->printQuotation($quotation);
            }

        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        return $printService->printProposal($proposal);
    }
}
