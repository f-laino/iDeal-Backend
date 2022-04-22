<?php

namespace App\Http\Controllers\Cms;

use App\Models\Car;
use App\Common\Models\Activity\Logger;
use App\Models\Fee;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\CommissionIndexerCreateRequest;
use App\Http\Requests\Cms\CommissionIndexerUpdateRequest;
use App\Http\Requests\Cms\FeeCreateRequest;
use App\Http\Requests\Cms\FeeUpdateRequest;
use Illuminate\Http\Request;

class FeeController extends CmsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indexers = Fee::paginate(self::$pagination);
        return view('fee.index', compact('indexers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $segments = Car::$segments;
        return view('fee.create', compact('segments'));
    }

    /**
     * Store a newly created resource in storage.
     * @param FeeUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeeCreateRequest $request)
    {
        Logger::request('FeeController@Store', $request);
        try{
            $index = new Fee;
            $index->broker = strtolower($request->broker);
            $index->pattern = json_encode($this->getPatternInput($request));
            $index->saveOrFail();
            Logger::activity('FeeController@Store', $request, $index);
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('commission.edit', ['commission' => $index->id])->with('success', 'Generatore creato con successo');

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
            $index = Fee::findOrFail($id);
            $pattern = json_decode($index->pattern, true);
        } catch (\Exception $exception){
            return redirect()->route('commission.index')->with('error', 'Elenco commissioni non trovato');
        }

        return view('fee.edit', compact('index', 'pattern'));

    }

    /**
     * Update the specified resource in storage
     *
     * @param FeeUpdateRequest $request
     * @param Fee $index
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FeeUpdateRequest $request, $index)
    {
        Logger::request('FeeController@Update', $request);
        try{
            $index = Fee::find($index);
            $oldIndex = $index->replicate();

            $index->update([
                'broker' => strtolower($request->broker),
                'pattern' => json_encode($this->getPatternInput($request)),
            ]);
            Logger::activity('FeeController@Update', $request, $index, $oldIndex);
        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return  redirect()->route('commission.edit',['commission' => $index->id])->with('success', 'Elenco commissioni aggiornato con successo.');

    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try{
            Logger::request('FeeController@Update', $request);
            $index = Fee::find($id);

            Logger::activity('FeeController@Update', $request, $index);
            $index->delete();

        } catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
        return redirect()->route('commission.index')->with('success', 'Elenco commissioni eliminato con successo.');
    }

    /**
     * Get all commission matrix inputs
     * @param Request $request
     * @return array $patter
     */
    private function getPatternInput(Request $request){
        $pattern = [
            'segment_a' => $request->get('segment_a', 0),
            'segment_b' => $request->get('segment_b', 0),
            'segment_c' => $request->get('segment_c', 0),
            'segment_d' => $request->get('segment_d', 0),
            'segment_e' => $request->get('segment_e', 0),
        ];

        return $pattern;
    }
}
