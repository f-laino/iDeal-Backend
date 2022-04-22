<?php

namespace App\Http\Requests\Cms;

use Illuminate\Foundation\Http\FormRequest;

class AgentStoreRequest extends FormRequest
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
        return [
            "email" => "required|string|email|max:255|unique:agents,email",
            "phone" => "nullable|numeric|min:6|unique:agents,phone",
            "group" => "required|numeric|exists:groups,id",
            "fee_percentage" => "required|numeric",
            "business_name" => "required_without:name|max:255",
            "name" => "required_without:business_name|max:255",
            'notes' => 'nullable|min:3|max:1000',
            'contact_info' => 'nullable|min:3|max:1000',
        ];
    }
}
