<?php

namespace App\Http\Requests\Api;

use App\Models\AgentToken;
use App\Http\Requests\ApiRequest;

class AgentUpdateProfileRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'business_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ];
    }
}
