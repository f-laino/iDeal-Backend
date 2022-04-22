<?php

namespace App\Transformer;

use App\Models\Agent;

/**
 * @OA\Schema(
 *  schema="Agent",
 *  type="object",
 *  @OA\Property(property="code", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="business_name", type="string"),
 *  @OA\Property(property="email", type="string", format="email"),
 *  @OA\Property(property="phone", type="string"),
 *  @OA\Property(property="commission", type="integer", example="1"),
 *  @OA\Property(property="open_quotations", type="integer"),
 *  @OA\Property(property="won_quotations", type="integer"),
 *  @OA\Property(property="lost_quotations", type="integer")
 * )
 */
class AgentTransformer extends BaseTransformer
{
    /**
     * Turn this item object into a generic array
     *
     * @param Brand $item
     * @return array
     */
    public function transform(Agent $agent)
    {
        return [
            'code' => (int)$agent->id,
            'name' => (string)$agent->name,
            'business_name' => (string)$agent->business_name,
            'email' => (string)$agent->email,
            'phone' => (string)$agent->phone,
            'commission' => 1,
            'open_quotations' => $agent->openQuotations(),
            'won_quotations' => $agent->wonQuotations(),
            'lost_quotations' => $agent->lostQuotations(),
        ];
    }
}
