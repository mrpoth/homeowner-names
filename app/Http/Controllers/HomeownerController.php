<?php

namespace App\Http\Controllers;

use App\Services\PersonParserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class HomeownerController extends Controller
{
    public function __invoke(PersonParserService $personParserService): Collection
    {
        return $personParserService->getHomeOwnerNames(Storage::get('examples-4-.csv'));
    }
}
