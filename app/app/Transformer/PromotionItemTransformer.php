<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Promotion;

/**
 * @OA\Schema(
 *  schema="PromotionItem",
 *  type="object",
 *  @OA\Property(property="promotion", type="integer", example="2"),
 *  @OA\Property(property="title", type="string", example="Offerte della settimana"),
 *  @OA\Property(property="description", type="string"),
 *  @OA\Property(property="hasAttachment", type="boolean"),
 *  @OA\Property(
 *      property="offers",
 *      type="object",
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OfferPromotion")
 *       )
 *  )
 * )
 */
class PromotionItemTransformer extends BaseTransformer
{
    private $agent;
    protected array $defaultIncludes = ['offers'];

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Turn this item object into a generic array
     * @param Promotion $promotion
     * @return array
     */
    public function transform(Promotion $promotion)
    {
        return [
            'promotion' => $promotion->id,
            'title' => $promotion->getCompiledTitle($this->agent),
            'description' => $promotion->getCompiledDescription($this->agent),
            'hasAttachment' => !is_null($promotion->attachment_uri),
        ];
    }

    public function includeOffers(Promotion $promotion)
    {
        $offers = $promotion->offers(true)->get();
        return $this->collection($offers, new OfferPromotionTransformer);
    }
}
