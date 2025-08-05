<?php

namespace App\Http\Controllers;

use App\Services\PersonParserService;

class HomeownerController extends Controller
{
    public function __invoke(PersonParserService $personParserService)
    {
        return $personParserService->getHomeOwnerNames(storage_path('app/private/examples-4-.csv'));
    }
}
