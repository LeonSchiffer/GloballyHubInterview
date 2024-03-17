<?php

namespace App\Http\Requests\Api\Client;
use App\Http\Requests\Api\BaseRequest;

class StoreClientRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required"],
            "gender" => ["required"],
            "phone" => ["required"],
            "email" => ["required", "email"],
            "address" => ["required"],
            "nationality" => ["required"],
            "dob" => ["required", "date"],
            "educational_background" => ["required"],
            "preferred_contact_mode" => ["required"],
        ];
    }
}
