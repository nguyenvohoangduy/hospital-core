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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'ten'               => 'required|string|unique:danh_muc_thuoc_vat_tu,ten',
                    'nhom_danh_muc_id'  => 'required|int',
                    'ma'                => 'required|string',
                    'don_vi_tinh_id'    => 'required|int',
                    'hoat_chat_id'      => 'required|int',
                    'loai_nhom'         => 'required|int',
                    'dong_goi'          => 'required|string',
                    'hang_san_xuat'     => 'required|string',
                    'nuoc_san_xuat'     => 'required|string',
                    'loai_nhom'         => 'required|string',
                    'gia'               => 'required|regex:/^\d*(\.\d{1,2})?$/'
                ];
                break;
            }
            case 'PUT':
                return [
                    'ten'               => 'required|string|unique:danh_muc_thuoc_vat_tu,ten,'.$this->id,
                    'nhom_danh_muc_id'  => 'required|int',
                    'ma'                => 'required|string',
                    'don_vi_tinh_id'    => 'required|int',
                    'hoat_chat_id'      => 'required|int',
                    'loai_nhom'         => 'required|int',
                    'dong_goi'          => 'required|string',
                    'hang_san_xuat'     => 'required|string',
                    'nuoc_san_xuat'     => 'required|string',
                    'loai_nhom'         => 'required|string',
                    'gia'               => 'required|regex:/^\d*(\.\d{1,2})?$/'
                ];
                break;
            default:
                break;
        }
    }
}
