<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Services\Coordinates;
use App\Services\DistanceMatrixService;
use App\Services\SearchResultService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchResult()
    {
        $geo = [
            'latitude' => 41.906539,
            'longitude' => 12.483015
        ];
        return response()->json((new SearchResultService('Rome', 'Italy', $geo))->exec());
    }

    public function findDistanceByTypes()
    {
        $latitude1 = 41.906539;
        $longitude1 = 12.483015;
        $latitude2 = 41.906397;
        $longitude2 = 12.48196;
        $transit = new DistanceMatrixService(new Coordinates($latitude1,$longitude1),new Coordinates($latitude2,$longitude2),'transit');
        $walk = new DistanceMatrixService(new Coordinates($latitude1,$longitude1),new Coordinates($latitude2,$longitude2),'walking');
        $car = new DistanceMatrixService(new Coordinates($latitude1,$longitude1),new Coordinates($latitude2,$longitude2),'driving');

        $result = [
            'public' => $transit->curlToGoogle(),
            'walk' => $walk->curlToGoogle(),
            'transfer' => $car->curlToGoogle()
        ];
        return response()->json($result);
    }

    public function test()
    {
        return response()->json('test');
    }
}
