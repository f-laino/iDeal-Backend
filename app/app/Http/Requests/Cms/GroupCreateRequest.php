<?php

namespace App\Http\Requests\Cms;

use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class GroupCreateRequest extends FormRequest
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
        $types = implode(',', Group::getTypes());

        return [
            "name" => "required|string|min:3|max:255|unique:groups,name,NULL,id,deleted_at,NULL",
            "fee_percentage" => "required|numeric",
            "email" => "nullable|string|email|max:255",
            "logo" => "nullable|image|max:5120",
            "type" => "required|in:$types",
        ];
    }
}
