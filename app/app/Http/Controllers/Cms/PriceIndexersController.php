<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\PriceIndexerCreateRequest;
use App\Http\Requests\Cms\PriceIndexerUpdateRequest;
use App\Models\Car;
use App\Models\Offer;
use App\Models\OfferAttributes;
use App\Models\PriceIndex;

/**
 * Class PriceIndexersController
 * @package App\Http\Controllers\Cms
 * @deprecated
 */
class PriceIndexersController extends CmsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indexers = PriceIndex::paginate(self::$pagination);
        return view('priceIndexers.index', compact('indexers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $segments = Car::$segments;
        return view('priceIndexers.create', compact('segments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceIndexerCreateRequest $request)
    {
        try{

            $segment = $request->segment;

            $pattern = [
                "1&15&24" => 1,
                "1&20&24" => 1,
                "1&25&24" => 1,
                "1&15&36" => 1,
                "1&20&36" => 1,
                "1&25&36" => 1,
                "0&15&24" => 1,
                "0&20&24" => 1,
                "0&25&24" => 1,
                "0&15&36" => 1,
                "0&20&36" => 1,
                "0&25&36" => 1,
            ];


            if (in_array($segment, PriceIndex::$smallSegments)){
                $pattern = [
                    "1&10&24" => 1,
                    "1&15&24" => 1,
                    "1&20&24" => 1,
                    "1&10&36" => 1,
                    "1&15&36" => 1,
                    "1&20&36" => 1,
                    "0&10&24" => 1,
                    "0&15&24" => 1,
                    "0&20&24" => 1,
                    "0&10&36" => 1,
                    "0&15&36" => 1,
                    "0&20&36" => 1,
                ];
            }



            $index = new PriceIndex;
            $index->broker = $request->broker;
            $index->segment = $request->segment;
            $index->pattern = json_encode($pattern);
            $index->secondary_pattern = json_encode($pattern);
            $index->saveOrFail();

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('price-indexers.edit', ['id' => $index->id])->with('success', 'Generatore creato con successo');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $index = PriceIndex::findOrFail($id);
            $pattern = json_decode($index->pattern, true);
            $secondaryPattern = json_decode($index->secondary_pattern, true);
        } catch (\Exception $exception){
            return redirect()->route('price-indexers.index')->with('error', 'Generatore non trovato');
        }

        return view('priceIndexers.edit', compact('index', 'segments', 'pattern', 'secondaryPattern'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PriceIndexerUpdateRequest $request, $id)
    {

        try{
            $index = PriceIndex::findOrFail($id);
            $segment = $index->segment;

            if (in_array($segment, PriceIndex::$smallSegments)){

                $validator = \Validator::make($request->all(), [
                    //main pattern
                    "1&10&24" => "required|numeric",
                    "1&15&24" => "required|numeric",
                    "1&20&24" => "required|numeric",
                    "1&10&36" => "required|numeric",
                    "1&15&36" => "required|numeric",
                    "1&20&36" => "required|numeric",
                    "0&10&24" => "required|numeric",
                    "0&15&24" => "required|numeric",
                    "0&20&24" => "required|numeric",
                    "0&10&36" => "required|numeric",
                    "0&15&36" => "required|numeric",
                    "0&20&36" => "required|numeric",

                    //secondary pattern
                    "secondary_1&10&24" => "required|numeric",
                    "secondary_1&15&24" => "required|numeric",
                    "secondary_1&20&24" => "required|numeric",
                    "secondary_1&10&36" => "required|numeric",
                    "secondary_1&15&36" => "required|numeric",
                    "secondary_1&20&36" => "required|numeric",
                    "secondary_0&10&24" => "required|numeric",
                    "secondary_0&15&24" => "required|numeric",
                    "secondary_0&20&24" => "required|numeric",
                    "secondary_0&10&36" => "required|numeric",
                    "secondary_0&15&36" => "required|numeric",
                    "secondary_0&20&36" => "required|numeric",

                ]);
                if ($validator->fails())
                    return redirect()->back()->withErrors($validator)->withInput();

                //main pattern
                $pattern = [
                    "1&10&24" => $request->get("1&10&24", 1),
                    "1&15&24" => $request->get("1&15&24", 1),
                    "1&20&24" => $request->get("1&20&24", 1),
                    "1&10&36" => $request->get("1&10&36", 1),
                    "1&15&36" => $request->get("1&15&36", 1),
                    "1&20&36" => $request->get("1&20&36", 1),
                    "0&10&24" => $request->get("0&10&24", 1),
                    "0&15&24" => $request->get("0&15&24", 1),
                    "0&20&24" => $request->get("0&20&24", 1),
                    "0&10&36" => $request->get("0&10&36", 1),
                    "0&15&36" => $request->get("0&15&36", 1),
                    "0&20&36" => $request->get("0&20&36", 1),
                ];

                $secondaryPattern = [
                    "1&10&24" => $request->get("secondary_1&10&24", 1),
                    "1&15&24" => $request->get("secondary_1&15&24", 1),
                    "1&20&24" => $request->get("secondary_1&20&24", 1),
                    "1&10&36" => $request->get("secondary_1&10&36", 1),
                    "1&15&36" => $request->get("secondary_1&15&36", 1),
                    "1&20&36" => $request->get("secondary_1&20&36", 1),
                    "0&10&24" => $request->get("secondary_0&10&24", 1),
                    "0&15&24" => $request->get("secondary_0&15&24", 1),
                    "0&20&24" => $request->get("secondary_0&20&24", 1),
                    "0&10&36" => $request->get("secondary_0&10&36", 1),
                    "0&15&36" => $request->get("secondary_0&15&36", 1),
                    "0&20&36" => $request->get("secondary_0&20&36", 1),
                ];

            } else {

                $validator = \Validator::make($request->all(), [
                    //main pattern
                    "1&15&24" => "required|numeric",
                    "1&20&24" => "required|numeric",
                    "1&25&24" => "required|numeric",
                    "1&15&36" => "required|numeric",
                    "1&20&36" => "required|numeric",
                    "1&25&36" => "required|numeric",
                    "0&15&24" => "required|numeric",
                    "0&20&24" => "required|numeric",
                    "0&25&24" => "required|numeric",
                    "0&15&36" => "required|numeric",
                    "0&20&36" => "required|numeric",
                    "0&25&36" => "required|numeric",

                    //secondary pattern
                    "secondary_1&15&24" => "required|numeric",
                    "secondary_1&20&24" => "required|numeric",
                    "secondary_1&25&24" => "required|numeric",
                    "secondary_1&15&36" => "required|numeric",
                    "secondary_1&20&36" => "required|numeric",
                    "secondary_1&25&36" => "required|numeric",
                    "secondary_0&15&24" => "required|numeric",
                    "secondary_0&20&24" => "required|numeric",
                    "secondary_0&25&24" => "required|numeric",
                    "secondary_0&15&36" => "required|numeric",
                    "secondary_0&20&36" => "required|numeric",
                    "secondary_0&25&36" => "required|numeric",
                ]);

                if ($validator->fails())
                    return redirect()->back()->withErrors($validator)->withInput();

                //main pattern
                $pattern = [
                    "1&15&24" => $request->get("1&15&24", 1),
                    "1&20&24" => $request->get("1&20&24", 1),
                    "1&25&24" => $request->get("1&25&24", 1),
                    "1&15&36" => $request->get("1&15&36", 1),
                    "1&20&36" => $request->get("1&20&36", 1),
                    "1&25&36" => $request->get("1&25&36", 1),
                    "0&15&24" => $request->get("0&15&24", 1),
                    "0&20&24" => $request->get("0&20&24", 1),
                    "0&25&24" => $request->get("0&25&24", 1),
                    "0&15&36" => $request->get("0&15&36", 1),
                    "0&20&36" => $request->get("0&20&36", 1),
                    "0&25&36" => $request->get("0&25&36", 1),
                ];

                $secondaryPattern = [
                    "1&15&24" => $request->get("secondary_1&15&24", 1),
                    "1&20&24" => $request->get("secondary_1&20&24", 1),
                    "1&25&24" => $request->get("secondary_1&25&24", 1),
                    "1&15&36" => $request->get("secondary_1&15&36", 1),
                    "1&20&36" => $request->get("secondary_1&20&36", 1),
                    "1&25&36" => $request->get("secondary_1&25&36", 1),
                    "0&15&24" => $request->get("secondary_0&15&24", 1),
                    "0&20&24" => $request->get("secondary_0&20&24", 1),
                    "0&25&24" => $request->get("secondary_0&25&24", 1),
                    "0&15&36" => $request->get("secondary_0&15&36", 1),
                    "0&20&36" => $request->get("secondary_0&20&36", 1),
                    "0&25&36" => $request->get("secondary_0&25&36", 1),
                ];
            }



            $index->update([
                'broker' => $request->broker,
                'pattern' => json_encode($pattern),
                'secondary_pattern' => json_encode($secondaryPattern),
            ]);
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return  redirect()->route('price-indexers.edit',['id' => $index->id])->with('success', 'Generatore aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            PriceIndex::find($id)->delete();

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('price-indexers.index')->with('success', 'Generatore eliminato con successo.');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function calculate($id){
        try{
            $indexer = PriceIndex::findOrFail($id);
            $parentOffers = Offer::getByBroker($indexer->broker)->whereHas('car', function($query) {
                $query->where('segmento', $this->segment);
            })->get();

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

        $cnt = 0;
        $errors = 0;
        try{
            foreach ($parentOffers as $parent){
                $parent->generateChildOffers($indexer);
                $cnt ++;
            }

        } catch (\Exception $exception){
            \Log::channel('offers')->error("Creazione multiofferte da matrice id $indexer->id. " . "Offer id: $parent->id " . $exception->getMessage()  );
            $errors++;
        }

        return back()->with('success', "$cnt offerte aggiornate con successo :) $errors errori generati durante l'aggiornamento :(");
    }
}
