<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Support\Str;

class ClientDto extends ExcelDto
{
    const GENDERS = [
        "Male",
        "Female",
        "Others",
    ];

    const PREFERRED_CONTACT_MODE = [
        "Phone",
        "Email"
    ];

    private $excel_column_key_pair = [
        "A" => "ulid",
        "B" => "name",
        "C" => "gender",
        "D" => "phone",
        "E" => "email",
        "F" => "address",
        "G" => "nationality",
        "H" => "dob",
        "I" => "education_background",
        "J" => "preferred_contact_mode",
        "K" => "created_at",
    ];

    public static function getFileName(): string
    {
        return "client";
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
        public string $education_background,
        public ?string $preferred_contact_mode,
        public string $created_at
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
            $data["education_background"],
            $data["preferred_contact_mode"] ?? "",
            now()->format("Y-m-d H:i:s")
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
            "I" => $this->education_background,
            "J" => $this->preferred_contact_mode,
            "K" => $this->created_at,
        ];
    }

    public static function fromRow(array $row)
    {
        return new self(...$row);
    }
}
