<?php

namespace App\Http\Requests;

class UpdatePermissionsFormRequest extends ApiFormRequest
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
            'service_id'            => 'required|int',
            'policy_id'             => 'required|int',
            'benh_vien_id'          => 'required|int'
        ];
    }
}
