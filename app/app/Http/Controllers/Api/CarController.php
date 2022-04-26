<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarCategory;
use App\Models\Fuel;
use App\Http\Controllers\ApiController;
use App\Image;
use App\Transformer\Car\BrandTransformer;
use App\Transformer\Car\ModelTransformer;
use App\Transformer\Car\VersionTransformer;
use App\Transformer\CarTransformer;
use App\Transformer\ErrorResponseTransformer;
use App\Transformer\ImageTransformer;
use App\Transformer\SuccessResponseTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use PHPUnit\Runner\Exception;

class CarController extends ApiController
{
    private $eurotaxService;

    public function __construct(Manager $fractal)
    {
        parent::__construct($fractal);
        $this->eurotaxService = app('eurotax');
    }

    /**
     * Get list of car brands
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Car"},
     *   path="/car/brands",
     *   summary="get list of car brands",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/CarBrand")
     *       )
     *     )
     *   )
     * )
     */
    public function brands()
    {
        $brands = Brand::all();
        return $this->respondWithCollection($brands, new BrandTransformer);
    }

    /**
     * Get list of brand models
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Car"},
     *   path="/car/models",
     *   summary="get list of brand models",
     *   @OA\Parameter(
     *      name="brand",
     *      required=true,
     *      in="query",
     *      example="ALF"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/CarModel")
     *       )
     *     )
     *   )
     * )
     */
    public function models(Request $request)
    {
        $brand = Brand::findBySlug($request->brand);
        $models = $this->eurotaxService->getModelli($brand->slug, null);
        return $this->respondWithCollection($models, new ModelTransformer);
    }

    /**
     * Get list of model verions
     *
     * @param string $model
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Car"},
     *   path="/car/versions/{model}",
     *   summary="get list of model verions",
     *   @OA\Parameter(
     *      name="model",
     *      required=true,
     *      in="path",
     *      example="2124"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/CarModelVersion")
     *       )
     *     )
     *   )
     * )
     */
    public function versions(string $model)
    {
        $versions = $this->eurotaxService->getVersioni($model, null, null, null);
        return $this->respondWithCollection($versions, new VersionTransformer);
    }

    /**
     * Get list of model verions
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     *
     * @OA\Get(
     *   tags={"Car"},
     *   path="/car",
     *   summary="get list of model verions",
     *   @OA\Parameter(
     *      name="motornet",
     *      required=true,
     *      in="query",
     *      example="ALF7011"
     *   ),
     *   @OA\Parameter(
     *      name="eurotax",
     *      required=true,
     *      in="query",
     *      example="1139195"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/Car"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="KO",
     *     @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="codice_motornet and codice_eurotax fields are required")
     *     )
     *   )
     * )
     */
    public function getCar(Request $request)
    {
        $car = new Car;
        $car = $car->saveOrFail([
            'codice_motornet' => $request->motornet,
            'codice_eurotax' => $request->eurotax,
        ]);
        return $this->respondWithItem($car, new CarTransformer);
    }

    /**
     * Store a car
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Car"},
     *   path="/car",
     *   summary="store a car",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="brand", type="string", description="brand slug"),
     *       @OA\Property(property="fuel", type="string", description="fuel slug"),
     *       @OA\Property(property="category", type="string", description="category slug"),
     *       @OA\Property(property="modello", type="string"),
     *       @OA\Property(property="allestimento", type="string"),
     *       @OA\Property(property="segmento", type="string"),
     *       @OA\Property(property="cilindrata", type="string"),
     *       @OA\Property(property="cavalli_fiscali", type="string"),
     *       @OA\Property(property="descrizione_trazione", type="string"),
     *       @OA\Property(property="desc_motore", type="string"),
     *       @OA\Property(property="hp", type="string"),
     *       @OA\Property(property="kw", type="string"),
     *       @OA\Property(property="euro", type="string"),
     *       @OA\Property(property="emissioni_co2", type="string"),
     *       @OA\Property(property="consumo_medio", type="string"),
     *       @OA\Property(property="descrizione_cambio", type="string"),
     *       @OA\Property(property="larghezza", type="string"),
     *       @OA\Property(property="lunghezza", type="string"),
     *       @OA\Property(property="bagagliaio", type="string"),
     *       @OA\Property(property="posti", type="string"),
     *       @OA\Property(property="porte", type="string"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/Car"
     *       )
     *     )
     *   ),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function addCar(Request $request)
    {
        /** @var Agent $agent */
        $agent = auth('api')->user();
        /** @var Brand $brand */
        $brand = Brand::findBySlug($request->brand);
        /** @var Fuel $fuel */
        $fuel = Fuel::findBySlug($request->fuel);
        /** @var CarCategory $category */
        $category = CarCategory::findBySlug($request->category);

        $code = str_random(4);
        $code = strtoupper($code);
        $codiceMotornet = $brand->slug . $code;
        $codiceEurotax = $agent->id . $code . 'CUSTOM';

        $car = new Car;
        $car->brand_id = $brand->id;
        $car->category_id = $category->id;
        $car->fuel_id = $fuel->id;
        $car->codice_motornet = $codiceMotornet;
        $car->codice_eurotax = $codiceEurotax;
        $car->codice_gruppo_storico = $agent->id . $brand->id;
        $car->descrizione_gruppo_storico = $brand->name;
        $car->codice_serie_gamma = $agent->myGroup->id;
        $car->descrizione_serie_gamma = $request->modello;
        $car->codice_modello = $code;
        $car->descrizione_modello = $request->modello;
        $car->cod_gamma_mod = $agent->id . $code;
        $car->modello = $request->modello;
        $car->allestimento = $request->allestimento;
        $car->segmento = $request->get('segmento', 'A');
        $car->cilindrata = $request->cilindrata;
        $car->cavalli_fiscali = $request->cavalli_fiscali;
        $car->descrizione_trazione= $request->descrizione_trazione;
        $car->tipo_motore = $request->desc_motore;
        $car->desc_motore = $request->desc_motore;
        $car->hp = $request->hp;
        $car->kw = $request->kw;
        $car->euro = $request->get('euro', 6);
        $car->emissioni_co2 = $request->emissioni_co2;
        $car->consumo_medio = $request->consumo_medio;
        $car->alimentazione = $fuel->name;
        $car->codice_cambio = $request->descrizione_cambio;
        $car->nome_cambio = $request->descrizione_cambio;
        $car->descrizione_cambio = $request->descrizione_cambio;
        $car->consumo_urbano = $request->consumo_medio;
        $car->larghezza = $request->larghezza;
        $car->lunghezza = $request->lunghezza;
        $car->bagagliaio = $request->bagagliaio;
        $car->posti = $request->get('posti', 5);
        $car->porte = $request->get('porte', 5);
        $car->neo_patentati	 = true;

        try {
            $car->save();
        } catch (Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
        $response = [ ];
        return $this->respondWithItem($car, new CarTransformer);
    }

    /**
     * List car images
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *   tags={"Car"},
     *   path="/car/images",
     *   summary="list car images",
     *   @OA\Parameter(
     *      name="car",
     *      required=true,
     *      in="query",
     *      example="435"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Image")
     *       )
     *     )
     *   ),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function getImages(Request $request)
    {
        try {
            $car = Car::findOrFail($request->car);
            return $this->respondWithCollection($car->images, new ImageTransformer);
        } catch (\Exception $exception) {
            return $this->respondWithItem($exception, new ErrorResponseTransformer);
        }
    }

    /**
     * Store car images
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *   tags={"Car"},
     *   path="/car/images",
     *   summary="store car images",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *        mediaType="multipart/form-data",
     *        @OA\Schema(
     *          type="object",
     *          @OA\Property(property="car", type="integer", example="435"),
     *          @OA\Property(property="file", type="string", format="binary")
     *        )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/Image")
     *       )
     *     )
     *   ),
     *   @OA\Response(response="500", ref="#/components/schemas/Error500")
     * )
     */
    public function addImage(Request $request)
    {
        $car = Car::find($request->car);
        $files = $request->allFiles();
        foreach ($files as $file) {
            try {
                $image = Image::add($car, $file);
                return $this->respondWithItem($image, new ImageTransformer);
            } catch (\Exception $exception) {
                return $this->respondWithItem($exception, new ErrorResponseTransformer);
            }
        }
        $response = [ "message" => 'File uploaded' ];
        return $this->respondWithItem($response, new SuccessResponseTransformer);
    }
}
