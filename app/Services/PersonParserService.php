<?php

namespace App\Services;

use App\ValueObjects\Person;
use Illuminate\Support\Collection;

class PersonParserService
{
    public function __construct(private CsvParserService $csvParser) {}

    public function getHomeOwnerNames(string $input): Collection
    {
        $namesFromCsv = $this->csvParser->parse($input);
        $people = $this->generatePersons($namesFromCsv);

        return $people;
    }

    private function generatePersons(array $names): Collection
    {
        $people = collect($names)->flatMap(function (string $name) {
            $nameArray = preg_split('/\s*(?:and|&)\s*/i', $name);
            $nameParts = explode(' ', $name);
            $isCouple = count($nameArray) > 1;

            return $isCouple
                ? $this->handleCouples($nameArray)
                : [$this->createPerson($nameParts)];
        });

        return $people;
    }

    private function handleCouples($nameArray): array
    {
        $firstPersonName = explode(' ', $nameArray[0]);
        $secondPersonName = explode(' ', $nameArray[1]);
        if (count($firstPersonName) > 1) {
            $person1 = $this->createPerson($firstPersonName);
            $person2 = $this->createPerson($secondPersonName);
        } else {
            $person2 = $this->createPerson($secondPersonName);
            $person1 = new Person(
                $firstPersonName[0],
                null,
                null,
                $person2->getLastName()
            );
        }

        return [$person1, $person2];
    }

    private function createPerson($nameParts): Person
    {
        $title = $nameParts[0];
        $initial = strlen($nameParts[1]) <= 2 ? str_replace('.', '', $nameParts[1]) : null;
        $firstName = $initial === null && count($nameParts) > 2 ? $nameParts[1] : null;
        $lastName = count($nameParts) > 2 ? $nameParts[2] : $nameParts[1];

        return new Person(
            $title,
            $initial,
            $firstName,
            $lastName
        );
    }
}
