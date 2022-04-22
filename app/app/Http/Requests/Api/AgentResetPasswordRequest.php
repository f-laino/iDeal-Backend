<?php

namespace App\Http\Requests\Api;

use App\Models\AgentToken;
use App\Http\Requests\ApiRequest;

class AgentResetPasswordRequest extends ApiRequest
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
        $length = AgentToken::$_LENGTH;
        return [
            'token' => "required|string|size:$length|exists:agent_tokens,token",
            'password' => "required|confirmed|string|min:6",
        ];
    }
}
