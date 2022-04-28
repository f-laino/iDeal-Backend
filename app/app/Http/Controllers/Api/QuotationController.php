<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\Attachment;
use App\Models\Customer;
use App\Factories\CrmFactory;
use App\Models\Group;
use App\Http\Controllers\ApiController;
use App\Notifications\QuotationAdded;
use App\Notifications\QuotationCreated;
use App\Models\Offer;
use App\Models\Proposal;
use App\Models\Quotation;
use App\Exports\QuotationsExport;
use App\Facades\Search;
use App\Services\AttachmentService;
use App\Services\Files\TemporaryStorageService;
use App\Services\PrintService;
use App\Transformer\ErrorResponseTransformer;
use App\Transformer\ProposalItemTransformer;
use App\Transformer\ProposalTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use League\Fractal\Manager;
use Storage;
use SplFileInfo;

/**
 * Il termine deal viene usato come alias di quotation
 * Class QuotationController
 * @package App\Http\Controllers\Api
 */
class QuotationController extends ApiController
{
    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
    }


    /**
     * Ritorna un elenco di preventivi creati in un dato intervallo di tempo
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Quotation"},
     *   path="/quotations/interval",
     *   summary="get a list of quotations by interval",
     *   @OA\Parameter(
     *     name="interval",
     *     in="query",
     *     required=true,
     *     @OA\Schema(enum={"today", "days",  "months", "year"})
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   ),
     *   @OA\Response(response=404, description="Not Found")
     * )
     */
    public function getByInterval(Request $request)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        $interval = $request->get('interval', null);

        list($current, $previous) = $this->getDates($interval);

        $quotations = $agent->proposals()->where('created_at', '>=', $current)
            ->orderBy('created_at', 'desc')->limit(9)->get();

        return $this->respondWithCollection($quotations, new ProposalItemTransformer());
    }

    /**
     * Gestisce una richiesta di nuovo preventivo
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @throws \Throwable
     *
     * @OA\Post(
     *   tags={"Quotation"},
     *   path="/quote",
     *   summary="store new quotation by proposal",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"proposalId"},
     *       @OA\Property(property="proposalId", type="integer")
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
        Log::info('STORE QUOTATION REQUEST', [ 'request' => $request->all() ]);

        try {
            $proposalId = $request->proposalId;

            /** @var Agent $agent */
            $agent = auth('api')->user();

            /** @var Group $group */
            $group = $agent->myGroup;

            /** @var Proposal $proposal */
            $proposal = Proposal::findOrFail($proposalId);

            /** @var Offer $offer */
            $offer = $proposal->offer;

            if ($offer->trashed()) {
                throw new \Exception('Offer not valid');
            }

            /** @var Quotation $quotation */
            $quotation = Quotation::createFromProposal($proposal, $offer);

            //send quotations to crm
            $crmFactory = CrmFactory::create($quotation);
            $crmFactory->createDeal($quotation);

            //Controllo se l'utente ha tutti i doc e imposto il flag sul CRM a si se neccessario
            if ($quotation->hasMandatoryDocuments()) {
                $fields = ["documenti_inviati_intermediario" => 'Si'];
                $crmFactory->updateDeal($quotation, $fields);
            }

            if (!empty($group) && !empty($group->notification_email)) {
                $quotation->notify(new QuotationCreated($group));
            }

            if ($offer->isCustom()) {
                $quotation->notify(new QuotationAdded($agent));
            }
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        $proposal = Proposal::findOrFail($proposalId);

        return $this->respondWithItem($proposal, new ProposalTransformer);
    }

    /**
     * Upload quotation documents
     *
     * @param Request $request
     * @param AttachmentService $attachmentService
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     * @OA\Post(
     *   tags={"Quotation"},
     *   path="/quotations/documents/upload",
     *   summary="upload quotation documents",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(
     *          type="object",
     *          required={"file"},
     *          @OA\Property(property="file_type", type="string", example="carta_identita"),
     *          @OA\Property(property="file", type="array",
     *              @OA\Items(type="string", format="binary")
     *          )
     *        )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         default="File uploaded"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Error",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         default="File not uploaded"
     *       ),
     *       @OA\Property(property="files", type="array", @OA\Items())
     *     )
     *   ),
     * )
     * @author George Son
     */
    public function attachDocument(
        Request $request,
        AttachmentService $attachmentService,
        TemporaryStorageService $temporaryStorage
    )
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        //leggo i parametri
        $quotationId = $request->get('quotation');
        $fileType = $request->get('file_type', 'unknown');

        $files = $request->allFiles();

        //Preparo le entita coninvolte nell'operazione
        try {
            /** @var Quotation $quotation */
            $quotation = $agent->quotations()
                        ->where("quotations.id", $quotationId)
                        ->firstOrFail();

            /** @var Proposal $proposal */
            $proposal = $quotation->proposal;

            /** @var Customer $customer */
            $customer = $proposal->customer;

            /** @var Offer $offer */
            $offer = $proposal->offer;

        } catch (\Exception $exception) {
            return response()->json(
                ['message' => 'Quotation not found'],
                Response::HTTP_BAD_REQUEST
            );
        }

        //valido le entita
        if ($offer->trashed()) {
            return response()->json(
                ['message' => 'Offer not valid'],
                Response::HTTP_BAD_REQUEST
            );
        }

        //inizializzo connessione CRM
        $crmFactory = CrmFactory::create($quotation);

        /* inizio la gestione degli attachments */
        try {
            /** @var UploadedFile $file */
            foreach ($files as $file) {

                //salvo il file nello storage dedicato
                $attachment = $attachmentService->addCustomerAttachment($customer, $file, $fileType);

                /* inoltro i file al sistema crm */

                //le integrazione funzionano solo con SplFileInfo
                $fl = $temporaryStorage->storeAndRetrieve($file, $attachment);
                $flPath = $fl->getPath();

                //invio il file
                $crmFactory->addFile($fl, $customer);

                //remove file from storage
                $temporaryStorage->removeFile($flPath);
            }
        } catch (\Exception $exception) {

            Log::channel('docs')
                ->error("Upload file fail with error: " . $exception->getMessage() .
                    " code: " . $exception->getCode());

            return response()->json(
                [
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        /* end gestione degli attachments */

        //Controllo se l'utente ha tutti i doc e imposto il flag sul CRM a si se neccessario
        if ($quotation->hasMandatoryDocuments()) {
            $fields = [ "documenti_inviati_intermediario" => 'Si'];
            $crmFactory->updateDeal($quotation, $fields);
        }

        return response()->json(['message' => 'File uploaded'], Response::HTTP_OK);
    }

    /**
     * Download quotation pdf
     *
     * @param int $id
     * @param PrintService $printService
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Quotation"},
     *   path="/quotations/{id}/attachment",
     *   summary="download quotation pdf",
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
    public function attachment($id, PrintService $printService)
    {
        try {
            /** @var Quotation $quotation */
            $quotation = Quotation::findOrFail($id);
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }

        return $printService->printQuotation($quotation);
    }

    /**
     * Download quotations excel
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Quotation"},
     *   path="/quotations/export",
     *   summary="download quotations list",
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="parameters", type="array")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\Header(header="Content-Disposition", @OA\Schema(type="string"), description="attachment; filename=preventivi.xlsx")
     *   )
     * )
     */
    public function export(Request $request)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();

        /** @var \Illuminate\Support\Collection $proposals */
        $proposals = Search::quotationsApi($request, $agent, false);

        return (new QuotationsExport($proposals))->download('preventivi.xlsx');
    }

    /**
     * Update quotation status
     *
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *   tags={"Quotation"},
     *   path="/quotations/{id}/status",
     *   summary="update quotation status",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"status"},
     *       @OA\Property(property="status", type="string")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK"
     *   )
     * )
     */
    public function updateStatus($id, Request $request){

        /** @var Agent $agent */
        $agent = auth('api')->user();

        $status = $request->get('status');

        try {
            /** @var Quotation $quotation */
            $quotation = $agent->quotations()->where("quotations.id", $id)->firstOrFail();
            $quotation->update(["status" => strtoupper($status)]);

            $crmFactory = CrmFactory::create($quotation);
            $crmFactory->updateDeal($quotation,
                ["status" => strtolower($status)]
            );

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        $response = ['status' => $quotation->status];
        return response()->json(['message' => 'Status updated'], 200);
    }
}
