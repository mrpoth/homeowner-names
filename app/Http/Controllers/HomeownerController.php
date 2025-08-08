<?php

namespace App\Http\Controllers;

use App\Services\PersonParserService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class HomeownerController extends Controller
{
    public function __invoke(PersonParserService $personParserService): Collection
    {
        return $personParserService
            ->getHomeOwnerNames(
                File::get(resource_path('data/examples-4-.csv'))
            );
    }
}
