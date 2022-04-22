<?php

namespace App\Http\Controllers\Cms;

use App\Models\Car;
use App\Models\CarAccessory;
use App\Http\Requests\Cms\AccessoryUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarAccessoriesController extends Controller
{

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id){
       try{
           /** @var CarAccessory $accessory */
           $accessory = CarAccessory::findOrFail($id);
           /** @var Car $car */
           $car = $accessory->car;

       } catch (\Exception $exception){
           return redirect()->back()->withErrors( $exception->getMessage() );
       }
       $allowedTypes = CarAccessory::$ALLOWED_TYPES;
       $allowedTypes = array_combine($allowedTypes, $allowedTypes);
       return view('accessories.edit', compact('accessory', 'car', 'allowedTypes'));
    }

    /**
     * @param AccessoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AccessoryUpdateRequest $request, $id){
        try{
            /** @var CarAccessory $accessory */
            $accessory = CarAccessory::findOrFail($id);
            $accessory->update([
                "type" => $request->type,
                "price" => $request->price,
                "available" => $request->available,
                "description" => $request->description,
                "standard_description" => $request->standard_description,
                "short_description" => $request->short_description,
            ]);
        } catch (\Exception $exception){
            return redirect()->back()->withErrors( $exception->getMessage() );
        }
        return back()->with('success', 'Accessorio modifcato con successo');

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request){
        /** @var CarAccessory $accessory */
        $accessory = CarAccessory::findOrFail($request->accessory);
        $oldStatus = $accessory->available;
        $status = $accessory->update(
            [
                'available'=>!$oldStatus
            ]
        );
        return response()->json(['status' => $status]);
    }

    /**
     * Aggiorna gli accessori di un allestimento dal webservice
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function updateFromSource($id){
        try{
            $car = Car::findOrFail($id);
            CarAccessory::deleteByCar($car->id);
            CarAccessory::storeFromWebservice($car);
        } catch (\Exception $exception){
            return redirect()->back()->withErrors( $exception->getMessage() );
        }
        return  redirect()->route('car.edit',['car' => $id]);
    }
}
