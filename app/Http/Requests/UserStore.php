<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStore extends FormRequest
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
            'firstName' => 'required | alpha |max:255',
            'lastName' => 'required | alpha |max:255',
            'email' => "required | email | unique:users,email,".$this->id.",id| max:255",
            'dateOfBirth' => 'required | date',
            'street' => 'required |max:255',
            'city' => 'required | alpha |max:255',
            'country' => 'required | alpha |max:255 ',
            'postalCode' => 'required | numeric |digits:6',
            'id' => '',
            'profilePic' => 'file|mimes:png,jpg,jpeg,webp|max:2048',
            'password' => '',
            'roles' => '',
            'course' => ''

        ];
    }
}
