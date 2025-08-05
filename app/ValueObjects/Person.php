<?php

namespace App\ValueObjects;

final class Person
{
    public function __construct(
        private string $title,
        private ?string $initial,
        private ?string $firstName,
        private string $lastName
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getInitial(): ?string
    {
        return $this->initial;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'first_name' => $this->firstName,
            'initial' => $this->initial,
            'last_name' => $this->lastName,
        ];
    }
}
