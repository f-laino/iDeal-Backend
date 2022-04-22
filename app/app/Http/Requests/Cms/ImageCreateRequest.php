<?php

namespace App\Http\Requests\Cms;

use App\Models\Image;
use Illuminate\Foundation\Http\FormRequest;

class ImageCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $positions = implode(',', Image::getPositions());
        return [
            'type' => "required|in:$positions",
            'image_alt' => 'nullable|string',
            'image' => "required|image|max:5120",
        ];
    }
}