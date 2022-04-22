<?php

namespace App\Transformer;

use App\Models\Agent;
use App\Models\Group;
use Illuminate\Support\Carbon;

/**
 * @OA\Schema(
 *  schema="Profile",
 *  type="object",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="business_name", type="string"),
 *  @OA\Property(property="email", type="string", format="email"),
 *  @OA\Property(property="phone", type="string"),
 *  @OA\Property(property="contact_info", type="string"),
 *  @OA\Property(property="logo", type="string"),
 *  @OA\Property(property="role", type="integer"),
 *  @OA\Property(property="group_name", type="string"),
 *  @OA\Property(property="group_code", type="integer"),
 *  @OA\Property(property="group_type", type="string"),
 *  @OA\Property(property="created_at", type="string", format="date"),
 *   @OA\Property(property="canCloseOffer", type="integer"),
 * )
 */
class ProfileTransformer extends BaseTransformer
{
    /**
     * Turn this agent object into a generic array
     *
     * @param Agent $agent
     * @return array
     */
    public function transform(Agent $agent)
    {
        /** @var Carbon $createdAt */
        $createdAt = $agent->created_at;

        $data = [
            'code' => $agent->id,
            'name' => (string)$agent->name,
            'business_name' => (string)$agent->business_name,
            'email' => (string)$agent->email,
            'phone' => (string)$agent->phone,
            'contact_info' => (string)$agent->contact_info,
            'logo' => $agent->getLogo(),
            'role' => (int) $agent->isGroupLeader(),
            'group_name' => '',
            'group_code' => '',
            'group_type' => '',
            'created_at' => $createdAt->toDateString(),
            'canCloseOffer' => (int)($agent->isEklyAgent()),
        ];

        if ($agent->myGroup()->exists()) {
            /** @var Group $group */
            $group = $agent->myGroup;
            $data['group_name'] = $group->name;
            $data['group_code'] = $group->id;
            $data['group_type'] = $group->type;
        }

        return $data;
    }
}
