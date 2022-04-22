<?php

namespace App\Http\Requests\Api;

use App\Models\AgentToken;
use App\Http\Requests\ApiRequest;

class AgentActivationRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $length = AgentToken::$_LENGTH;
        return [
            'token' => "required|string|size:$length|exists:agent_tokens,token",
            'password' => "required|confirmed|string|min:8",
        ];
    }
}
