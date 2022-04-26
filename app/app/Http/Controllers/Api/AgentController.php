<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\AgentUpdateProfileRequest;
use App\Transformer\AgentTransformer;
use App\Transformer\ProfileTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery\Exception;

/**
 * @OA\Schema(
 *  schema="AgentStastics",
 *  type="object",
 *  @OA\Property(
 *      property="data",
 *      type="object",
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="role", type="string"),
 *      @OA\Property(property="open", type="integer"),
 *      @OA\Property(property="won", type="integer"),
 *      @OA\Property(property="lost", type="integer"),
 *      @OA\Property(property="potential_profit", type="integer"),
 *      @OA\Property(property="effective_profit", type="integer"),
 *      @OA\Property(property="new_quotations", type="integer"),
 *      @OA\Property(property="new_customers", type="integer"),
 *      @OA\Property(property="previous_potential_profit", type="integer"),
 *      @OA\Property(property="previous_effective_profit", type="integer"),
 *      @OA\Property(property="previous_quotations", type="integer"),
 *      @OA\Property(property="previous_customers", type="integer")
 *  )
 * )
 */
class AgentController extends ApiController
{

    /**
     * Get stats about current agent
     *
     * @param Request $request
     *
     * @return @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Agent"},
     *   path="/agent/statistics",
     *   summary="get stats about current agent",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AgentStastics")
     *   )
     * )
     */
    public function statistics(Request $request)
    {
        $agent = auth('api')->user();
        return $this->getAgentStatisics($agent, $request);
    }

    /**
     * Get stats about agent
     *
     * @param string $code
     * @param Request $request
     *
     * @return @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Agent"},
     *   path="/agent/{code}/statistics",
     *   summary="get stats about agent",
     *   @OA\Parameter(
     *      name="code",
     *      required=true,
     *      in="path",
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/AgentStastics")
     *   )
     * )
     */
    public function memberStatistics(string $code, Request $request)
    {
        $agent = auth('api')->user();

        if (!$agent->isGroupLeader()) {
            return response()->json(['msg' =>"Unauthorized"], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $groupAgent = $agent->findTeamMember($code);
            return $this->getAgentStatisics($groupAgent, $request);
        } catch (Exception $exception) {
            return response()->json(['msg' => 'Agent not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get the authenticated agent profile
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Agent"},
     *   path="/agent/profile",
     *   summary="get current agent data",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Profile")
     *   )
     * )
     */
    public function profile()
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();
        return $this->respondWithItem($agent, new ProfileTransformer);
    }

    /**
     * Update agent profile
     *
     * @param AgentUpdateProfileRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   tags={"Agent"},
     *   path="/agent/profile",
     *   summary="update current agent data",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name"},
     *       @OA\Property(property="name", type="string"),
     *       @OA\Property(property="business_name", type="string"),
     *       @OA\Property(property="phone", type="string"),
     *       @OA\Property(property="contact_info", type="string"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(ref="#/components/schemas/Profile")
     *   ),
     *   @OA\Response(response=500, description="*"),
     * )
     */
    public function updateProfile(AgentUpdateProfileRequest $request)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        $name = $request->get('name');
        $businessName = $request->get('business_name');
        $phone = $request->get('phone');
        $contactInfo = $request->get('contact_info');

        if (is_null($businessName) && $request->exists('business_name')) {
            $businessName = '';
        }

        if (is_null($phone) && $request->exists('phone')) {
            $phone = '';
        }

        if (is_null($contactInfo) && $request->exists('contact_info')) {
            $contactInfo = '';
        }

        try {
            $agent->updateProfile($name, $businessName, $phone, $contactInfo);
        } catch (\Exception $exception) {
            return $this->setStatusCode(500)->respondWithError($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respondWithItem($agent, new ProfileTransformer);
    }

    /**
     * Get stats about all agents
     *
     * @param Request $request
     *
     * @return @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Agent"},
     *   path="/agent/statistics/members",
     *   summary="get stats about all agents",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *      @OA\Property(
     *        property="data",
     *        type="object",
     *        @OA\Property(property="quotations", type="integer"),
     *        @OA\Property(property="previous_quotations", type="integer"),
     *        @OA\Property(property="new_customers", type="integer"),
     *        @OA\Property(property="previous_customers", type="integer"),
     *        @OA\Property(property="open", type="integer"),
     *        @OA\Property(property="won", type="integer"),
     *        @OA\Property(property="lost", type="integer"),
     *        @OA\Property(property="profit", type="integer"),
     *        @OA\Property(property="previous_profit", type="integer"),
     *        @OA\Property(
     *          property="quotations_grouped",
     *          type="object",
     *          @OA\Property(
     *              property="2021",
     *              type="object",
     *              @OA\Property(property="1", type="integer"),
     *              @OA\Property(property="2", type="integer"),
     *              @OA\Property(property="3", type="integer"),
     *              @OA\Property(property="4", type="integer"),
     *              @OA\Property(property="5", type="integer"),
     *              @OA\Property(property="6", type="integer"),
     *              @OA\Property(property="7", type="integer"),
     *              @OA\Property(property="8", type="integer"),
     *              @OA\Property(property="9", type="integer"),
     *              @OA\Property(property="10", type="integer"),
     *              @OA\Property(property="11", type="integer"),
     *              @OA\Property(property="12", type="integer"),
     *          )
     *        )
     *       )
     *      )
     *   )
     * )
     */
    public function membersStatistics(Request $request)
    {
        $agent = auth('api')->user();

        if (!$agent->isGroupLeader()) {
            return response()->json(['msg' =>"Unauthorized"], Response::HTTP_UNAUTHORIZED);
        }

        $interval = $request->get('interval', null);
        list($current, $previous) = $this->getDates($interval);

        $opens = $agent->quotationsAsLeaderQuery()
                        ->where('status', 'OPEN')
                        ->whereHas('Proposal', function ($query) use ($current) {
                            $query->where('created_at', '>=', $current);
                        })->get();
        $wons = $agent->quotationsAsLeaderQuery()
                        ->where('status', 'WON')
                        ->whereHas('Proposal', function ($query) use ($current) {
                            $query->where('created_at', '>=', $current);
                        })->get();
        $lost = $agent->quotationsAsLeaderQuery()
                        ->where('status', 'LOST')
                        ->whereHas('Proposal', function ($query) use ($current) {
                            $query->where('created_at', '>=', $current);
                        })->get();

        $newQuotations = $agent->quotationsAsLeaderQuery()
                                ->whereHas('Proposal', function ($query) use ($current) {
                                    $query->where('created_at', '>=', $current);
                                });
        $newCustomers = $newQuotations->count();

        $previousOpens = $agent->quotationsAsLeaderQuery()
                                ->where('status', 'OPEN')
                                ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                    $query->whereBetween('created_at', [$previous, $current]);
                                })->get();
        $previousWons = $agent->quotationsAsLeaderQuery()
                                ->where('status', 'WON')
                                ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                    $query->whereBetween('created_at', [$previous, $current]);
                                })->get();
        $previousLosts = $agent->quotationsAsLeaderQuery()
                                ->where('status', 'LOST')
                                ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                    $query->whereBetween('created_at', [$previous, $current]);
                                })->get();

        $previousQuotations =  $agent->quotationsAsLeaderQuery()
                                    ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                        $query->whereBetween('created_at', [$previous, $current]);
                                    });
        $previousCustomers = $previousQuotations->count();


        $profit = 0;
        $previousProfit = 0;

        foreach ($wons as $won) {
            $profit += $won->getFee();
        }
        foreach ($previousWons as $prevWon) {
            $previousProfit += $prevWon->getFee();
        }

        $quotationsGrouped = $agent->quotationsAsLeaderMonthGrouped();
        $data = [
            'quotations' => $opens->count() + $wons->count() + $lost->count(),
            'previous_quotations' => $previousOpens->count() + $previousWons->count() + $previousLosts->count(),
            'new_customers' => $newCustomers,
            'previous_customers' => $previousCustomers,
            'profit' => $profit,
            'previous_profit' => $previousProfit,
            'open' => $opens->count(),
            'won' => $wons->count(),
            'lost' => $lost->count(),
            'quotations_grouped' => $quotationsGrouped,

        ];
        return response()->json(['data' => $data], Response::HTTP_OK);
    }


    /**
     * Get members list
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Agent"},
     *   path="/agent/members",
     *   summary="get agents list",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Agent")
     *       ),
     *     ),
     *   ),
     *   @OA\Response(response=400, description="Unauthorized")
     * )
     */
    public function members()
    {
        $agent = auth('api')->user();
        if ($agent->isGroupLeader()) {
            $agents = $agent->myAgents()->where('id', '!=', $agent->id)->get();
            return $this->respondWithCollection($agents, new AgentTransformer);
        }
        return response()->json(['msg' =>"Unauthorized"], 401);
    }

    /**
     * Build agent statistics
     *
     * @param Agent $agent
     * @param Request $request
     *
     * @return @return \Illuminate\Http\JsonResponse
     */
    private function getAgentStatisics(Agent $agent, Request $request)
    {
        $interval = $request->get('interval', null);

        list($current, $previous) = $this->getDates($interval);

        $opens = $agent->quotations()
                        ->where('status', 'OPEN')
                        ->whereHas('Proposal', function ($query) use ($current) {
                            $query->where('created_at', '>=', $current);
                        })->get();
        $wons = $agent->quotations()
                        ->where('status', 'WON')
                        ->whereHas('Proposal', function ($query) use ($current) {
                            $query->where('created_at', '>=', $current);
                        })->get();
        $lost = $agent->quotations()
                        ->where('status', 'LOST')
                        ->whereHas('Proposal', function ($query) use ($current) {
                            $query->where('created_at', '>=', $current);
                        })->get();

        $newQuotations = $agent->quotations()
                                ->whereHas('Proposal', function ($query) use ($current) {
                                    $query->where('created_at', '>=', $current);
                                });
        $newCustomers = $newQuotations->count();

        $previousOpens = $agent->quotations()
                                ->where('status', 'OPEN')
                                ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                    $query->whereBetween('created_at', [$previous, $current]);
                                })->get();
        $previousWons = $agent->quotations()
                                ->where('status', 'WON')
                                ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                    $query->whereBetween('created_at', [$previous, $current]);
                                })->get();

        $previousQuotations =  $agent->quotations()
                                    ->whereHas('Proposal', function ($query) use ($previous, $current) {
                                        $query->whereBetween('created_at', [$previous, $current]);
                                    });
        $previousCustomers = $previousQuotations->count();


        $potentialProfit = 0;
        $effectiveProfit = 0;

        $previousPotentialProfit = 0;
        $previousEffectiveProfit = 0;

        foreach ($opens as $open) {
            $potentialProfit += $open->getFee();
        }
        foreach ($wons as $won) {
            $effectiveProfit += $won->getFee();
        }

        foreach ($previousOpens as $prevOpen) {
            $previousPotentialProfit += $prevOpen->getFee();
        }
        foreach ($previousWons as $prevWon) {
            $previousEffectiveProfit += $prevWon->getFee();
        }

        $data = [
            'name' => !empty($agent->business_name) ? $agent->business_name : $agent->name,
            'role' => 'Agente',
            'open' => $opens->count(),
            'won' => $wons->count(),
            'lost' => $lost->count(),
            'potential_profit' => $potentialProfit,
            'effective_profit' => $effectiveProfit,
            'new_quotations' => $newQuotations->count(),
            'new_customers' => $newCustomers,
            'previous_potential_profit' => $previousPotentialProfit,
            'previous_effective_profit' => $previousEffectiveProfit,
            'previous_quotations' => $previousQuotations->count(),
            'previous_customers' => $previousCustomers,
        ];

        return response()->json(['data' => $data], Response::HTTP_OK);
    }
}
