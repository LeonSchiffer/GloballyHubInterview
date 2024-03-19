<?php

namespace App\Http\Requests\Api\Client;

use App\Models\Client;
use App\Http\Requests\Api\BaseRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

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
            "gender" => ["required", Rule::in(Client::GENDERS)],
            "phone" => ["required"],
            "email" => ["required", "email"],
            "address" => ["required"],
            "nationality" => ["required"],
            "dob" => ["required", "date", "before: 10 years ago"],
            "education_background" => ["required"],
            "preferred_contact_mode" => ["nullable", Rule::in(Client::PREFERRED_CONTACT_MODE)],
        ];
    }

    /**
     * Custom messages for some validation
     */
    public function messages(): array
    {
        return [
            "gender.in" => "Gender can only be " . implode(", ", array_keys(Client::GENDERS)),
            "dob.before" => "You must be alteast 10 years old",
            "preferred_contact_mode.in" => "Preferred contact mode can only be " . implode(", ", Client::PREFERRED_CONTACT_MODE)
        ];
    }

    /**
     * Custom attribute name for some payloads
     */
    public function attributes(): array
    {
        return [
            "dob" => "date of birth"
        ];
    }
}
