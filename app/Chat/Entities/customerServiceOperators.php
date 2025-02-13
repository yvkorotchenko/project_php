<?php

declare(strict_types=1);

namespace App\Chat\Entities;

class customerServiceOperators implements \JsonSerializable
{
    public function __construct(
        public int                  $id,
        public string               $name,
        public CustomerServices     $company,
        public string               $created_at,
        public string               $updated_at,
        public string               $email,
        public string               $password,
    ) {}

    public function jsonSerialize()
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'customer_service_id'   => $this->company,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'email'                 => $this->email,
            'password'              => $this->password,
        ];
    }
}