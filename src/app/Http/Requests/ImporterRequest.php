<?php

namespace App\Http\Requests;

use App\Rules\ServerMaxFileSize;
use Illuminate\Foundation\Http\FormRequest;

class ImporterRequest extends FormRequest
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
            'products' => [
                // pre-check illuminate/Validation/Validator.php in:549
                // will return error if file has a larger volume than allowed on the server
                'required',
                'mimes:xls,xlsx',
                new ServerMaxFileSize(),
            ],
        ];
    }
}
