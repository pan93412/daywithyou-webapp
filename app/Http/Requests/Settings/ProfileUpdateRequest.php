<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /**
             * 使用者姓名
             */
            'name' => ['required', 'string', 'max:255'],

            /**
             * 使用者信箱
             */
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            /**
             * 郵遞區號
             */
            'zip' => ['string', 'max:255', 'nullable'],
            /**
             * 電話
             */
            'phone' => ['string', 'max:255', 'nullable'],
            /**
             * 地址
             */
            'address' => ['string', 'max:255', 'nullable'],
            /**
             * 城市
             */
            'city' => ['string', 'max:255', 'nullable'],
        ];
    }
}
