<?php

namespace App\Http\Requests;

class UpdatePolicyFormRequest extends ApiFormRequest
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
        return [
            'service_id'        => 'required|int',
            'name'              => 'required|string',
            'key'               => 'required|string',
            'uri'               => 'required|string',
            'method'            => 'required|string',
            'access_type'       => 'required|int'            
        ];
    }
}
