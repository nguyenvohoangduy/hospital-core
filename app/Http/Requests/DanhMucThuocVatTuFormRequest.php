<?php

namespace App\Http\Requests;

class DanhMucThuocVatTuFormRequest extends ApiFormRequest
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
            'ten'               => 'required|string',
            'nhom_danh_muc_id'  => 'required|int',
            'ma'                => 'required|string',
            'don_vi_tinh_id'    => 'required|int',
            'hoat_chat_id'      => 'required|int',
            'nong_do'           => 'required|string',
            'duong_dung'        => 'required|string',
            'dong_goi'          => 'required|string',
            'hang_san_xuat'     => 'required|string',
            'nuoc_san_xuat'     => 'required|string',
            'loai_nhom'         => 'required|string',
            'gia'               => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'gia_bhyt'          => 'nullable|regex:/^\d*(\.\d{1,2})?$/',
            'gia_nuoc_ngoai'    => 'nullable|regex:/^\d*(\.\d{1,2})?$/'
        ];
    }
}
