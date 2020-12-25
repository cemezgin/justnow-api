<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Services\Coordinates;
use App\Services\DistanceMatrixService;
use App\Services\SearchResultService;
use App\Services\TransportationAlgorithmService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchResult(Request $request)
    {
        $geo = [
            'latitude' => 41.906539,
            'longitude' => 12.483015
        ];
        return response()->json((new SearchResultService('Rome', 'Italy', $geo))->exec());
    }

    public function findDistanceByTypes(Request $request)
    {
        $latitude1 = $request->query('lat1');
        $longitude1 = $request->query('long1');
        $latitude2 = $request->query('lat2');
        $longitude2 = $request->query('long2');

        $result = (new TransportationAlgorithmService())->get($latitude1,$longitude1,$latitude2,$longitude2);

        return response()->json($result);
    }

    public function test()
    {
        return response()->json('test');
    }
}
