<?php

namespace App\Http\Controllers\Cms;

use App\Models\ContractualCategory;
use App\Http\Controllers\CmsController;
use Illuminate\Http\Request;

class CategoryController extends CmsController
{
    public function index()
    {
        $categories = ContractualCategory::paginate(self::$pagination);
        return view('category.index', compact('categories'));
    }


    public function show($id)
    {
        return redirect()->route('category.edit', ['category'=>$id]);
    }


    public function edit(Request $request, $id){

        try{
            $category = ContractualCategory::findOrFail($id);
        } catch (\Exception $exception){
            dd($exception->getMessage());
        }

        return view('category.edit', compact('category'));
    }
}
