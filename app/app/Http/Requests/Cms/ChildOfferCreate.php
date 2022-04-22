<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\ApiRequest;

class ChildOfferCreate extends ApiRequest
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
            'id' => 'required|exists:offers',
            "monthly_rate" => "required|numeric|min:0|max:10000",
            "duration" => "required|numeric|min:12|max:100",
            "deposit" => "required|numeric|min:0|max:100000",
            "distance" => "required|numeric|min:0|max:100000",
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');
        return $data;
    }
}
