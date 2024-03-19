<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Handle a failed validation attempt.
     * This abstract class was created to return a single string for each validation instead of array of strings
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => __("Invalid form data"),
                'type' => 'validation',
                'errors' => $this->dataResopnsewithKey($validator)
            ], 422)
        );
    }

    public function dataResopnsewithKey($validator)
    {
        $errors = [];
        foreach ($validator->errors()->messages() as $key => $value) {
            $errors[$key] = is_array($value) ? $value[0] : $value;
        }
        return $errors;
    }
}
