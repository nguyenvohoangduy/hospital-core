<?php

namespace App\Http\Requests;

class MauHoiBenhFormRequest extends ApiFormRequest
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
            'ten_mau_hoi_benh'    => 'unique:mau_hoi_benh,ten_mau_hoi_benh',
        ];
    }
}