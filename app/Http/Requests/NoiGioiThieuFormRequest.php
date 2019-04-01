<?php

namespace App\Http\Requests;

class NoiGioiThieuFormRequest extends ApiFormRequest
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
            'ten'      => 'required|string',
            'dia_chi' => 'required|string',
            'loai' => 'nullable|boolean'
        ];
    }
}
