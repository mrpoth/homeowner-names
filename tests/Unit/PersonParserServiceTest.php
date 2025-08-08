<?php

namespace Tests\Unit;

use App\Services\CsvParserService;
use App\Services\PersonParserService;
use App\ValueObjects\Person;
use PHPUnit\Framework\TestCase;

class PersonParserServiceTest extends TestCase
{
    private PersonParserService $personParserService;

    protected function setUp(): void
    {
        parent::setUp();
        $csvParserService = new CsvParserService;
        $this->personParserService = new PersonParserService($csvParserService);
    }

    private function personToArray(Person $person): array
{
    return [
        'title'      => $person->getTitle(),
        'first_name' => $person->getFirstName(),
        'initial'    => $person->getInitial(),
        'last_name'  => $person->getLastName(),
    ];
}

    public function test_get_home_owner_names_with_single_person_no_initial(): void
    {
        $input = "homeowner,\nMr John Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith',
        ], $this->personToArray($result->first()));
    }

    public function test_get_home_owner_names_with_couples_full_names_no_initials(): void
    {
        $input = "homeowner,\nMr John Smith and Mrs Jane Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith',
        ], $this->personToArray($result->first()));
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => 'Jane',
            'initial' => null,
            'last_name' => 'Smith',
        ], $this->personToArray($result->get(1)));
    }

    public function test_get_home_owner_names_with_single_person_with_initial(): void
    {
        $input = "homeowner,\nMr J Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'J',
            'last_name' => 'Smith',
        ], $this->personToArray($result->first()));
    }

    public function test_get_home_owner_names_with_hyphenated_last_name(): void
    {
        $input = "homeowner,\nMrs Faye Hughes-Eastwood,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => 'Faye',
            'initial' => null,
            'last_name' => 'Hughes-Eastwood',
        ], $this->personToArray($result->first()));
    }

    public function test_get_home_owner_names_with_couple_using_ampersand(): void
    {
        $input = "homeowner,\nDr & Mrs Joe Bloggs,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Dr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Bloggs',
        ], $this->personToArray($result->first()));
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => 'Joe',
            'initial' => null,
            'last_name' => 'Bloggs',
        ], $this->personToArray($result->get(1)));
    }

    public function test_get_home_owner_names_with_multiple_couples(): void
    {
        $input = "homeowner,\nMr Tom Staff and Mr John Doe,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'Tom',
            'initial' => null,
            'last_name' => 'Staff',
        ], $this->personToArray($result->first()));
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Doe',
        ], $this->personToArray($result->get(1)));
    }

    public function test_get_home_owner_names_with_mr_and_mrs_combined_title(): void
    {
        $input = "homeowner,\nMr and Mrs Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Smith',
        ], $this->personToArray($result->first()));
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Smith',
        ], $this->personToArray($result->get(1)));
    }

    public function test_get_home_owner_names_with_initials_and_no_first_name(): void
    {
        $input = "homeowner,\nMr F. Fredrickson,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'F',
            'last_name' => 'Fredrickson',
        ], $this->personToArray($result->first()));
    }

    public function test_get_home_owner_names_with_null_values(): void
    {
        $input = "homeowner,\n,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(0, $result);
    }
}
