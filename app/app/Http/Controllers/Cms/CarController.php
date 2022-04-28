<?php

namespace App\Http\Controllers\Cms;

use App\Models\Brand;
use App\Models\Car;
use App\Models\CarCategory;
use App\Common\Models\Activity\Logger;
use App\Models\Fuel;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\CarStoreRequest;
use App\Http\Requests\Cms\CarUpdateRequest;
use App\Http\Requests\Cms\ImageCreateRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use function Aws\flatmap;

class CarController extends CmsController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cars = Car::paginate(self::$pagination);
        return view('car.index', compact('cars'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('car.edit', ['car' => $id]);
    }

    public function create(){
        $brands = Brand::pluck('name', 'id');
        $fuels = Fuel::pluck('name', 'id');
        $carCategories = CarCategory::pluck('name', 'id');
        $segments = Car::$segments;

        return view('car.create', compact('brands', 'fuels', 'carCategories', 'segments'));
    }

    public function store(CarStoreRequest $request){

        Logger::request('CarController@Store', $request);

        /** @var Brand $brand */
        $brand = Brand::find($request->brand);

        /** @var Fuel $fuel */
        $fuel = Fuel::find( $request->fuel );

        $code = Str::random(4);
        $code = strtoupper($code);
        $codiceMotornet = $brand->slug . $code;
        $codiceEurotax = $code . 'CUSTOM';

        $car = new Car;
        $car->brand_id = $brand->id;
        $car->category_id = $request->category;
        $car->fuel_id = $fuel->id;
        $car->codice_motornet = $codiceMotornet;
        $car->codice_eurotax = $codiceEurotax;
        $car->codice_gruppo_storico =  $brand->id;
        $car->descrizione_gruppo_storico = $brand->name;
        $car->codice_serie_gamma = $brand->name;
        $car->descrizione_serie_gamma = $request->modello;
        $car->codice_modello = $code;
        $car->descrizione_modello = $request->modello;
        $car->cod_gamma_mod =  $code;
        $car->modello = $request->modello;
        $car->allestimento = $request->allestimento;
        $car->segmento = $request->segmento;
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
        $car->neo_patentati = $request->get('neo_patentati', false);
        $car->batteria_kwh = $request->get('kwh');

        try{
            $car->save();
            Logger::activity('CarController@Store', $request, $car);
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('car.edit',[ 'car' => $car->id]);
    }

    /**
     * @param Car $car
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Car $car)
    {
        $offers = $car->offers;
        $equippedAccessories = $car->getEquippedAccessories();
        $optionalAccessories = $car->getOptionalAccessories();
        $images = $car->images;
        $colors = $car->getAvailableColors();
        $packs = $car->getAvailablePacks();
        $brands = Brand::pluck('name', 'id');
        $fuels = Fuel::pluck('name', 'id');
        $carCategories = CarCategory::pluck('name', 'id');
        $segments = Car::$segments;
        return view('car.edit', compact('car', 'offers', 'equippedAccessories', 'optionalAccessories', 'colors', 'packs', 'images', 'brands', 'fuels', 'carCategories', 'segments'));
    }

    /**
     * @param CarUpdateRequest $request
     * @param Car $car
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CarUpdateRequest $request, Car $car)
    {
        Logger::request('CarController@Update', $request);

        /** @var Brand $brand */
        $brand = Brand::find($request->brand);
        /** @var Fuel $fuel */
        $fuel = Fuel::find( $request->fuel );

        $oldCar = $car->replicate();
        try{
            $car->update([
            'brand_id' => $brand->id,
            'category_id' => $request->category,
            'fuel_id' => $request->fuel,
            'codice_gruppo_storico' => $fuel->id,
            'descrizione_gruppo_storico' => $brand->name,
            'codice_serie_gamma' => $brand->name,
            'descrizione_serie_gamma' => $request->modello,
            'descrizione_modello' => $request->modello,
            'modello' => $request->modello,
            'allestimento' => $request->allestimento,
            'segmento' => $request->segmento,
            'cilindrata' => $request->cilindrata,
            'cavalli_fiscali' => $request->cavalli_fiscali,
            'descrizione_trazione' => $request->descrizione_trazione,
            'tipo_motore' => $request->desc_motore,
            'desc_motore' => $request->desc_motore,
            'hp' => $request->hp,
            'kw' => $request->kw,
            'euro' => $request->euro,
            'emissioni_co2' => $request->emissioni_co2,
            'consumo_medio' => $request->consumo_medio,
            'alimentazione' => $fuel->name,
            'codice_cambio' => $request->descrizione_cambio,
            'nome_cambio' => $request->descrizione_cambio,
            'descrizione_cambio' => $request->descrizione_cambio,
            'consumo_urbano' => $request->consumo_medio,
            'larghezza' => $request->larghezza,
            'lunghezza' => $request->lunghezza,
            'bagagliaio' => $request->bagagliaio,
            'posti' => $request->posti,
            'porte' => $request->porte,
            'batteria_kwh' => $request->kwh,
            'neo_patentati' => $request->neo_patentati,
            ]);

            Logger::activity('CarController@Update', $request, $car, $oldCar);
        } catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage() );
        }

        return back()->with('success', 'Dettagli allestimento aggiornati con successo');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function addImage(Request $request, $id){
        try{
            $car = Car::findOrFail($id);
        } catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage() );
        }
        $positions = Image::$_POSITIONS;
        return view('image.create', compact('positions', 'car'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(ImageCreateRequest $request, $id){
        Logger::request('CarController@UploadImage', $request);
        $file = $request->file('image', NULL);
        $imageAlt = $request->get('image_alt', NULL);
        $type = $request->type;
        try{
            $car = Car::findOrFail($id);
            $image = Image::add($car, $file, $imageAlt, $type);
            Logger::activity('CarController@UploadImage', $request, $image);
        } catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage() );
        }
        return redirect()->route('car.edit', ['car' => $id]);
    }
}
