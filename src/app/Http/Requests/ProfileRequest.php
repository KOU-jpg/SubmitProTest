<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
        'profile_image.image'   => '画像ファイルを選択してください',
        'profile_image.mimes'   => '画像ファイルはJPEGまたはPNG形式のみアップロードできます',
        'profile_image.max'     => '画像サイズは2MB以内にしてください',
        ];
    }
}
