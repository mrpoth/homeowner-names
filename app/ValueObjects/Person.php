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

    // Optional: A method to get full name formatted nicely
    public function getFullName(): string
    {
        $parts = [$this->title, $this->firstName];

        if ($this->initial !== null) {
            $parts[] = $this->initial;
        }

        $parts[] = $this->lastName;

        return implode(' ', $parts);
    }
}
