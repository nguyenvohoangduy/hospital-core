<?php

namespace App\Http\Requests;

class HoatChatFormRequest extends ApiFormRequest
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
                    'ten'   => 'required|string|unique:hoat_chat'
                ];
                break;
            }
            case 'PUT':
                return [
                    'ten'   => 'required|string'
                ];
                break;
            case 'PATCH': {
                return [
                    'name'        => 'required|string',
              
                ];
                break;
            }
            default:
                break;
        }

    }
}
