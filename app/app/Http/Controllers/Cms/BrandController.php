<?php

namespace App\Http\Controllers\Cms;
use App\Http\Controllers\CmsController;
use App\Http\Requests\Cms\BrandUpdateRequest;
use App\Models\Brand;

class BrandController extends CmsController
{
    public function index()
    {
        $brands = Brand::paginate(self::$pagination);
        return view('brand.index', compact('brands'));
    }

    public function edit(Brand $brand)
    {
        return view('brand.edit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        try{
            $brand->update([
                'name' => $request->name,
                'title' => $request->title,
                'description' => $request->description,
                'logo' => $request->logo,
                'logo_alt' => $request->logo_alt,
            ]);
        }  catch (\Exception $exception){
            return redirect()->back()->withErrors( $exception->getMessage() );
        }

        return redirect()->route('brand.edit', ['brand' => $brand->id]);
    }
}
