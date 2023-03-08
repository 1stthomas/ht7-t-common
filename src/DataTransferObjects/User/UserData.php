<?php

namespace Ht7FfhsBsct\Common\DataTransferObjects\User;

class UserData
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email
    )
    {

    }
    /**
     * @param array{id: int, name: string, email: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new static(
            $data['id'],
            $data['name'],
            $data['email']
        );
    }
}
