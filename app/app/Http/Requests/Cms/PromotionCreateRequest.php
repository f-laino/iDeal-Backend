<?php

namespace App\Http\Requests\Cms;

use App\Models\Promotion;
use Illuminate\Foundation\Http\FormRequest;

class PromotionCreateRequest extends FormRequest
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
        $templates = implode(',', Promotion::getTemplates() );
        return [
            'title' => 'required|min:3|max:150',
            'description' => 'required|min:3|max:1000',
            'status' => 'required|boolean',
            'expires_at' => 'nullable|date',
            'attachment_uri' => "nullable|in:$templates"
        ];
    }
}
