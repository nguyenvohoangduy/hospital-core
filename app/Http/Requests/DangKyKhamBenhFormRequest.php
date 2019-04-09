<?php

namespace App\Http\Requests;



class DangKyKhamBenhFormRequest extends ApiFormRequest
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
            case 'GET': {
                return [];
            }
            case 'POST': {
                return [
                    'benh_vien_id' => 'required|integer',
                    'ho_va_ten' => 'required|string',
                    'ngay_sinh' => 'required|date_format:Y-m-d',
                    'gioi_tinh_id' => 'required',
                    'yeu_cau_kham_id' => 'required',
                    'tinh_thanh_pho_id' => 'nullable',
                    'quan_huyen_id' => 'nullable',
                    'phuong_xa_id' => 'nullable',
                    //'phong_id' => 'required',//|string|regex:/^[a-zA-Z]+$/u',
                    'khoa_id' => 'required',
                    'email_benh_nhan' => 'nullable|email'
                    //nếu có bh check bh so vs ngày hiện tại
                ];
            }
            default:
                break;
        }
    }
}
