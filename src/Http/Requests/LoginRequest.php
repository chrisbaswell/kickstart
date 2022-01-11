<?php

namespace Baswell\Kickstart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Baswell\Kickstart\Kickstart;

class LoginRequest extends FormRequest
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
            Kickstart::username() => 'required|string',
            'password' => 'required|string',
        ];
    }
}
