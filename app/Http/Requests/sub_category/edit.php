<?php

namespace App\Http\Requests\sub_category;

use Illuminate\Foundation\Http\FormRequest;

class edit extends FormRequest
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
            'image'             => 'nullable|mimes:jpeg,jpg,png,gif',
            'sub_cate.*.name'   => 'required|string',
        ];
    }
}
