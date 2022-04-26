<?php
namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Group;
use App\Http\Controllers\ApiController;
use App\Models\Promotion;
use App\Transformer\PromotionItemTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use PDF;

class PromotionController extends ApiController
{
    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }

    /**
     * Get list of promotions
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Promotion"},
     *   path="/promotion",
     *   summary="get list of promotions",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/PromotionItem")
     *       )
     *     )
     *   )
     * )
     */
    public function index()
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();
        $promotions = Promotion::getByStatus(true);
        return $this->respondWithCollection($promotions, new PromotionItemTransformer($agent));
    }

    /**
     * Download promotion pdf
     *
     * @param Request $request
     * @param string $code
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Promotion"},
     *   path="/promotion/{code}/download",
     *   summary="download promotion pdf",
     *   @OA\Parameter(
     *     name="code",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Header(header="Content-Disposition", @OA\Schema(type="string"), description="attachment; filename=promozione-{id}-{timestamp}.pdf")
     *   )
     * )
     */
    public function download(Request $request, string $code)
    {
        try {
            /** @var Agent $agent */
            $agent = auth('api')->user();

            /** @var Group $group */
            $group = $agent->myGroup;

            /** @var Promotion $promotion */
            $promotion = Promotion::where([['id', $code], ['status', true]])->firstOrFail();
            $title = $promotion->getCompiledTitle($agent);
            $description = $promotion->getCompiledDescription($agent);
            $offers = $promotion->offers(true)->get();

            $pdf = PDF::loadView($promotion->attachment_uri, compact('title', 'description', 'agent', 'group', 'promotion', 'offers'));
            $date = Carbon::now()->timestamp;
            $name = "promozione-{$promotion->id}-{$date}.pdf";

            return $pdf->setPaper('A4')->download($name);
        } catch (\Exception $exception) {
            return $this->respondWithArray([
                'error' => [
                    'code' => $exception->getCode(),
                    'http_code' => 500,
                    'message' => $exception->getMessage(),
                ]
            ]);
        }
    }
}
