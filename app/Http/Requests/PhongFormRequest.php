<?php

namespace App\Http\Requests;

class PhongFormRequest extends ApiFormRequest
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
            'khoa_id'              => 'required|int',
            'so_phong'              => 'required|int',
            'ma_nhom'                   => 'required|string',
            'ten_phong'                   => 'required|string',
            'loai_phong'                => 'required|int'
           
        ];
    }
}
