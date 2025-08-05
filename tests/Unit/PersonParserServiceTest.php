<?php

namespace Tests\Unit;

use App\Services\PersonParserService;
use App\Services\CsvParserService;
use PHPUnit\Framework\TestCase;

class PersonParserServiceTest extends TestCase
{
    private PersonParserService $personParserService;

    protected function setUp(): void
    {
        parent::setUp();
        $csvParserService = new CsvParserService();
        $this->personParserService = new PersonParserService($csvParserService);
    }

    public function testGetHomeOwnerNamesWithSinglePersonNoInitial(): void
    {
        $input = "homeowner,\nMr John Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith',
        ], $result->first());
    }

    public function testGetHomeOwnerNamesWithCouplesFullNamesNoInitials(): void
    {
        $input = "homeowner,\nMr John Smith and Mrs Jane Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Smith',
        ], $result->first());
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => 'Jane',
            'initial' => null,
            'last_name' => 'Smith',
        ], $result->get(1));
    }

    public function testGetHomeOwnerNamesWithSinglePersonWithInitial(): void
    {
        $input = "homeowner,\nMr J Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'J',
            'last_name' => 'Smith',
        ], $result->first());
    }

    public function testGetHomeOwnerNamesWithHyphenatedLastName(): void
    {
        $input = "homeowner,\nMrs Faye Hughes-Eastwood,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => 'Faye',
            'initial' => null,
            'last_name' => 'Hughes-Eastwood',
        ], $result->first());
    }

    public function testGetHomeOwnerNamesWithCoupleUsingAmpersand(): void
    {
        $input = "homeowner,\nDr & Mrs Joe Bloggs,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Dr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Bloggs',
        ], $result->first());
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => 'Joe',
            'initial' => null,
            'last_name' => 'Bloggs',
        ], $result->get(1));
    }

    public function testGetHomeOwnerNamesWithMultipleCouples(): void
    {
        $input = "homeowner,\nMr Tom Staff and Mr John Doe,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'Tom',
            'initial' => null,
            'last_name' => 'Staff',
        ], $result->first());
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => 'John',
            'initial' => null,
            'last_name' => 'Doe',
        ], $result->get(1));
    }

    public function testGetHomeOwnerNamesWithMrAndMrsCombinedTitle(): void
    {
        $input = "homeowner,\nMr and Mrs Smith,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(2, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Smith',
        ], $result->first());
        $this->assertEquals([
            'title' => 'Mrs',
            'first_name' => null,
            'initial' => null,
            'last_name' => 'Smith',
        ], $result->get(1));
    }


    public function testGetHomeOwnerNamesWithInitialsAndNoFirstName(): void
    {
        $input = "homeowner,\nMr F. Fredrickson,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(1, $result);
        $this->assertEquals([
            'title' => 'Mr',
            'first_name' => null,
            'initial' => 'F',
            'last_name' => 'Fredrickson',
        ], $result->first());
    }

    public function testGetHomeOwnerNamesWithNullValues(): void
    {
        $input = "homeowner,\n,\n";
        $result = $this->personParserService->getHomeOwnerNames($input);
        $this->assertCount(0, $result);
    }
}
