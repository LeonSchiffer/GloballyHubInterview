<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ClientDto
{
    const GENDERS = [
        "male" => "m",
        "female" => "f",
        "others" => "o"
    ];

    const PREFERRED_CONTACT_MODE = [
        "phone",
        "contact"
    ];

    public static function getFilePath()
    {
        return storage_path("app/client.csv");
    }

    public function __construct(
        public string $ulid,
        public string $name,
        public string $gender,
        public string $phone,
        public string $email,
        public string $address,
        public string $nationality,
        public string $dob,
        public string $educational_background,
        public string $preferred_contact_mode,
        public Carbon $created_at
    )
    {

    }

    public static function fromArray(array $data)
    {
        return new self(
            Str::ulid(),
            $data["name"],
            $data["gender"],
            $data["phone"],
            $data["email"],
            $data["address"],
            $data["nationality"],
            $data["dob"],
            $data["educational_background"],
            $data["preferred_contact_mode"],
            now()
        );
    }

    public function toCsv(): array
    {
        return [
            "A" => $this->ulid,
            "B" => $this->name,
            "C" => $this->gender,
            "D" => $this->phone,
            "E" => $this->email,
            "F" => $this->address,
            "G" => $this->nationality,
            "H" => $this->dob,
            "I" => $this->educational_background,
            "J" => $this->preferred_contact_mode,
            "K" => $this->created_at,
        ];
    }
}
