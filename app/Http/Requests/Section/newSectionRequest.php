<?php

namespace App\Http\Requests\Section;

use Illuminate\Foundation\Http\FormRequest;

class newSectionRequest extends FormRequest
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
            'title' => 'required|unique:sections|min:6|max:150',
            'objective' => 'required|min:6',
        ];
    }
}
