<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseCreateRequest extends FormRequest
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
            'title' => ['required', 'min:6', 'max:150', Rule::unique('courses')],
            'subTitle' => ['required', 'min:15', 'max:255', Rule::unique('courses')],
            'description' => ['required', 'min:20'],
            'language' => ['required'],
            'level' => ['required'],
            'category_id' => 'required',
            'image' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The category name is required.',
        ];
    }
}
