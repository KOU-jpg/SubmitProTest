<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{

    
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        'name'     => 'required|max:255',
        'postal_code'  => 'required|regex:/^\d{3}-\d{4}$/|size:8',
        'address'      => 'required',
        'building'     => 'required',
    ];
}
    public function messages()
{
    return [
        'name.required'     => 'ユーザー名を入力してください',
        'postal_code.required'  => '郵便番号を入力してください',
        'postal_code.regex'     => '郵便番号は「123-4567」の形式で入力してください',
        'postal_code.size'      => '郵便番号は8文字（例：123-4567）で入力してください',
        'address.required'      => '住所を入力してください',
        'building.required'     => '建物名を入力してください',
    ];
}
}
