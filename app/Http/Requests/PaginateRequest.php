<?php

namespace App\Http\Requests;

/**
 * Class PaginateRequest
 * @package App\Http\Requests
 */
class PaginateRequest extends Request
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
            //
        ];
    }

    /**
     * @return array
     */
    public function inputs()
    {
        $request = $this->all();
        if (! isset($request['order']) || ! $request['order']) {
            $request['order'] = 'desc';
        }
        if (! isset($request['take']) || ! $request['take']) {
            $request['take'] = 5;
        }
        if (! isset($request['skip']) || ! $request['skip']) {
            $request['skip'] = 0;
        }

        return $request;
    }
}
