<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Agent;
use App\Group;
use App\Models\Offer;
use App\Transformer\Catalog\OfferTransformer;
use Illuminate\Http\Request;

class CatalogController extends ApiController
{

    /**
     * Get catalog offer
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse|string
     *
     * @OA\Get(
     *   tags={"Catalog"},
     *   path="/catalog/{code}",
     *   summary="get catalog offer",
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
     *         type="object",
     *         ref="#/components/schemas/CatalogOffer"
     *       )
     *     )
     *   )
     * )
     */
    public function getOffer(string $code)
    {
        try {
            /** @var Offer $offer */
            $offer = Offer::findByCode($code);

            /** @var Agent $agent */
            $agent = auth('api')->user();

            if ($offer->canBeUpdated($agent)) {
                return $this->respondWithItem($offer, new OfferTransformer);
            } else {
                return $this->errorNotFound();
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Delete catalog offer
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse|string
     *
     * @OA\Delete(
     *   tags={"Catalog"},
     *   path="/catalog/{code}",
     *   summary="delete catalog offer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   )
     * )
     */
    public function deleteOffer(string $code)
    {
        try {
            /** @var Offer $offer */
            $offer = Offer::findByCode($code);

            /** @var \App\Agent $agent */
            $agent = auth('api')->user();

            if ($offer->canBeUpdated($agent)) {
                $offer->delete();
            } else {
                return response()->json(['msg' =>"Unauthorized"], 401);
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Duplicate catalog offer
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse|string
     *
     * @OA\Post(
     *   tags={"Catalog"},
     *   path="/catalog/{code}/clone",
     *   summary="duplicate catalog offer",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="array",
     *       @OA\Items(property="reference", type="string"),
     *       @OA\Items(property="notes", type="string"),
     *       @OA\Items(property="active", type="boolean"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="newCode",
     *         type="string"
     *       )
     *     )
     *   )
     * )
     */
    public function cloneOffer(Request $request, string $code)
    {
        $clones = $request->json()->all();
        $clonesCount = count($clones);

        if ($clonesCount < 1) {
            $clones[] = ['reference' => null, 'notes' => null, 'active' => true];
        }

        try {
            /** @var Offer $offer */
            $offer = Offer::findByCode($code);

            /** @var \App\Agent $agent */
            $agent = auth('api')->user();

            if ($offer->canBeUpdated($agent)) {
                $clonesCodes = [];

                foreach ($clones as $clone) {
                    $newOffer = $offer->replicate();
                    $newOffer->code = $offer->car->generateCode();
                    $newOffer->reference_code = $clone['reference'];
                    $newOffer->notes = $clone['notes'];
                    $newOffer->push();

                    $members = Group::getMembers($agent);
                    $newOffer->attachAgents($members, $clone['active']);

                    $clonesCodes[] = $newOffer->code;
                }

                if (count($clonesCodes) === 1) {
                    return response()->json(['newCode' => $clonesCodes[0]], 201);
                } else {
                    return response()->json(['newCodes' => $clonesCodes], 201);
                }
            } else {
                return response()->json(['msg' =>"Unauthorized"], 401);
            }
        } catch (\Exception $exception) {
            return response()->json(['msg' => $exception->getMessage()], 400);
        }
    }
}
