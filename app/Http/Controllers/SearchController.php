<?php

namespace App\Http\Controllers;

use App\Services\SearchResultService;
use App\Services\TransportationAlgorithmService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public $transportationAlgorithmService;

    public function __construct(TransportationAlgorithmService $transportationAlgorithmService)
    {
        $this->transportationAlgorithmService = $transportationAlgorithmService;
    }

    public function searchResult(Request $request)
    {
        $geo = [
            'latitude' => 41.906539,
            'longitude' => 12.483015
        ];
        $location = 'Rome';
        $country = 'Italy';
        $hour = $request->get('hour');
        $minute = $request->get('minute');

        return response()->json((new SearchResultService($location, $country, $geo))->exec());
    }

    public function findDistanceByTypes(Request $request)
    {
        $latitude1 = $request->query('lat1');
        $longitude1 = $request->query('long1');
        $latitude2 = $request->query('lat2');
        $longitude2 = $request->query('long2');

        $result = $this->transportationAlgorithmService->get($latitude1,$longitude1,$latitude2,$longitude2);

        return response()->json(['data' =>$result]);
    }
}
