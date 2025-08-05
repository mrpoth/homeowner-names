<?php

namespace App\Http\Controllers;

use App\Services\PersonParserService;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;

class HomeownerController extends Controller
{
    public function __invoke(PersonParserService $personParserService)
    {
        return $personParserService->getHomeOwnerNames(storage_path('app/private/examples-4-.csv'));
    }
}
