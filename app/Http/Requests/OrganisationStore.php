<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganisationStore extends FormRequest
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
            'name' => "required|unique:organisations,name,".$this->id.",id| max:255",
            'shortDescription' => 'required|max:255',
            'description' => 'max:1500',
            'logo' => 'file|mimes:png,jpg,jpeg,webp|max:2048',
            'id' => '',
            'user' => 'required',
            '_OPENING_TIME_' => 'required',
            '_CLOSING_TIME_' => 'required',
            '_CLASS_DURATION_' => 'required',
            '_LUNCH_BREAK_START_TIME_' => 'required',
            '_LUNCH_BREAK_END_TIME_' => 'required',
            '_BREAK_DURATION_' => 'required',
            '_NUMBER_OF_BREAK_' => 'required'
        ];
    }
}
