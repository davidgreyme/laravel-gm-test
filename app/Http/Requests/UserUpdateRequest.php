<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;

/**
 * Class UserUpdateRequest
 * @package App\Http\Requests
 */
class UserUpdateRequest extends Request
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
            'username' => 'unique:users',
            'email'    => 'unique:users|email',
            'password' => 'sometimes|required',
        ];
    }

    /**
     *  Hash password if password exist.
     */
    protected function passedValidation()
    {
        if ($this->password) {
            $this->replace(['password' => bcrypt($this->password)]);
        }
        if ($this->username) {
            $this->replace(['username' => Str::lower($this->username)]);
        }
    }
}
