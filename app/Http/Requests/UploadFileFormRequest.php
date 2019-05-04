<?php

namespace App\Http\Requests;

class UploadFileFormRequest extends ApiFormRequest
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
            'files.*' => 'mimes:mpga,jpeg,png,jpg,gif,svg,pdf,mp3,mpeg3|max:307200'
        ];
    }
}