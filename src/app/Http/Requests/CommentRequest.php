<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // 認証済みユーザーのみ許可
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'item_id' => ['required'],
            'comment' => ['required', 'max:255'],
        ];
    }
    public function messages(): array
    {
        return [
            'item_id.required' => '商品IDが指定されていません',
            'comment.required' => 'コメントを入力してください',
            'comment.max'      => 'コメントは255文字以内で入力してください',
        ];
    }
}
