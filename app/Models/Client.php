<?php

namespace App\Models;

use Illuminate\Support\Str;

class Client extends BaseExcelModel
{
    /**
     * The eligible genders. Use in StoreClientRequest
     * @see \App\Http\Requests\Api\Client\StoreClientRequest
     */
    const GENDERS = [
        "Male",
        "Female",
        "Others",
    ];

    /**
     * The eligible preferred contact modes. Use in StoreClientRequest
     * @see \App\Http\Requests\Api\Client\StoreClientRequest
     */
    const PREFERRED_CONTACT_MODE = [
        "Phone",
        "Email"
    ];

    /**
     * This abstract method needs to be implemented by all classes that extends BaseExcelModel
     * This is the name of the excel file that the data will be saved to
     * @see BaseExcelModel
     */
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

    /**
     * Create a new Client object from an array
     * Used in ClientController
     * @see \App\Http\Controllers\Api\V1\Client\ClientController
     */
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

    /**
     * Maps the elements in an array to its respective column in the CSV
     * @return array The array with excel's column name as key and the value to be inserted as value
     */
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

    /**
     * Returns a new object of the following class from the excel row
     * @return Client
     */
    public static function fromRow(array $row): self
    {
        return new self(...$row);
    }
}
