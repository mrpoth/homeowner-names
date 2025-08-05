<?php

namespace App\Services;

use App\ValueObjects\Person;


class PersonParserService
{

    public function getHomeOwnerNames(string $input)
    {
        $namesFromCsv = $this->csvParser($input);
        $this->generatePersons($namesFromCsv);
    }

    private function csvParser(string $filename): array
    {
        $rows = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data[0];
            }
            fclose($handle);
        }

        return $rows;
    }

    private function generatePersons(array $names)
    {
        $persons = [];
        foreach ($names as $name) {
            $nameArray = preg_split('/\s*(?:and|&)\s*/i', $name);
            $isCouple = count($nameArray) > 1;
            if ($isCouple) {
                $firstPersonName =  explode(' ', $nameArray[0]);
                $secondPersonName =  explode(' ', $nameArray[1]);
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
                $persons[] = $person1;
                $persons[] = $person2;
            } else {
                $nameParts =  explode(' ', $name);
                $person = $this->createPerson($nameParts);
                $persons[] = $person;
            }
        }

        return $persons;
    }

    private function createPerson($nameParts): Person
    {
        $title = $nameParts[0];
        $initial = strlen($nameParts[1]) <= 2 ? str_replace('.', '', $nameParts[1]) : null;
        $firstName = $initial === null && count($nameParts)  > 2 ? $nameParts[1] : null;
        $lastName = count($nameParts) > 2 ?  $nameParts[2] : $nameParts[1];
        return new Person(
            $title,
            $initial,
            $firstName,
            $lastName
        );
    }
}
