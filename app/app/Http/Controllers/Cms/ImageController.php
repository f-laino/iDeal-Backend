<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\ImageUpdateRequest;
use App\Models\Image;
use App\Models\Brand;
use Illuminate\Http\Request;

class ImageController extends CmsController
{
    public function index()
    {
        $images = Image::paginate(self::$pagination);
        $brands = Brand::pluck('name', 'id');
        return view('image.index', compact('images', 'brands'));
    }

    public function edit(Image $image)
    {
        $positions = Image::$_POSITIONS;
        $car = $image->car;
        return view('image.edit', compact('image', 'positions', 'car'));
    }

    public function update(ImageUpdateRequest $request, Image $image)
    {
        try{
            $image->update([
                'type' => $request->type,
                'image_alt' => $request->image_alt,
            ]);
        }  catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage() );
        }
        return redirect()->route('image.edit', ['image'=> $image->id])->with('success', 'Immagine aggiornata con successo');
    }

    public function destroy(Image $image)
    {
        try{
            $image->delete();
        } catch (\Exception $exception){
            return redirect()->route('image.edit', ['image'=> $image->id])->with('error', "Impossibile eliminare l'immagine selezionata");
        }
        return redirect()->route('image.index')->with('success', 'Immagine aggiornata con successo');
    }
}
