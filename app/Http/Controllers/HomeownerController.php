<?php

namespace App\Http\Controllers;

use App\Services\PersonParserService;
use Illuminate\Support\Collection;

class HomeownerController extends Controller
{
    public function __invoke(PersonParserService $personParserService): Collection
    {
        return $personParserService->getHomeOwnerNames(storage_path('app/private/examples-4-.csv'));
    }
}
