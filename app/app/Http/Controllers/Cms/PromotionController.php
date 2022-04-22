<?php

namespace App\Http\Controllers\Cms;

use App\Http\Requests\Cms\PromotionCreateRequest;
use App\Http\Requests\Cms\PromotionUpdateRequest;
use App\Models\Promotion;
use App\Http\Controllers\CmsController;

class PromotionController extends CmsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::paginate(self::$pagination);
        return view('promotion.index', compact('promotions'));
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id){
        return redirect()->route('promotion.edit', ['promotion'=>$id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = [
            true => "Attiva",
            false => "Non attiva"
        ];

        $templates = array_merge([ NULL => 'Seleziona' ], Promotion::$_TEMPLATES);
        return view('promotion.create', compact('status', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionCreateRequest $request)
    {
        $promotion = new Promotion;
        $promotion->title = $request->title;
        $promotion->description = $request->description;
        $promotion->status = $request->status;
        $promotion->attachment_uri = $request->get('attachment_uri', NULL);
        $promotion->expires_at = $request->get('expires_at', NULL);

        try{
            $promotion->saveOrFail();
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('promotion.edit',[ 'id' => $promotion->id])->with('success', 'Promozione creata con successo.');
    }


    /**
     * Show the form for editing the specified resource.
     * @param Promotion $promotion
     */
    public function edit(Promotion $promotion)
    {
        $status = [
            true => "Attiva",
            false => "Non attiva"
        ];
        $offers = $promotion->offers;
        $templates = array_merge([ NULL => 'Seleziona'], Promotion::$_TEMPLATES);
        return view('promotion.edit', compact('promotion', 'status', 'offers', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PromotionUpdateRequest $request, $id)
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'expires_at' => $request->get('expires_at', NULL),
                'attachment_uri' => $request->get('attachment_uri', NULL),
            ]);

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('promotion.edit',[ 'promotion' => $id])->with('success', 'Promozione aggiornata con successo.');
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
            /** @var Promotion $promotion */
            $promotion = Promotion::findOrFail($id);
            $promotion->detachOffers();
            $promotion->delete();
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('promotion.index')->with('success', 'Promozione eliminata con successo.');
    }
}
