<?php

namespace App\Services;

class TransportationAlgorithmService
{
    public function get($latitude1,$longitude1,$latitude2,$longitude2) {


        $transit = new DistanceMatrixService(new Coordinates($latitude1,$longitude1),new Coordinates($latitude2,$longitude2),'transit');
        $walk = new DistanceMatrixService(new Coordinates($latitude1,$longitude1),new Coordinates($latitude2,$longitude2),'walking');
        $car = new DistanceMatrixService(new Coordinates($latitude1,$longitude1),new Coordinates($latitude2,$longitude2),'driving');
        $publicTransportationInfo = $transit->curlToGoogle();
        $walkInfo = $walk->curlToGoogle();
        $carInfo = $car->curlToGoogle();

        if ($walkInfo['rows'][0]['elements'][0]['duration']['value'] < $carInfo['rows'][0]['elements'][0]['duration']['value']) {
            $best = 'walk';
        } elseif($carInfo['rows'][0]['elements'][0]['duration']['value'] < $publicTransportationInfo['rows'][0]['elements'][0]['duration']['value']){
            $best = 'transfer';
        } else {
            $best = 'public';
        }

        return [
            'destination_addresses' => $walkInfo['destination_addresses'],
            'origin_addresses' => $walkInfo['origin_addresses'],
            'public' => [
                $publicTransportationInfo['rows'][0]['elements'][0]['duration']['text'],
                $publicTransportationInfo['rows'][0]['elements'][0]['distance']['text']
            ],
            'walk' => [
                $walkInfo['rows'][0]['elements'][0]['duration']['text'],
                $walkInfo['rows'][0]['elements'][0]['distance']['text']
            ],
            'transfer' => [
                $carInfo['rows'][0]['elements'][0]['duration']['text'],
                $carInfo['rows'][0]['elements'][0]['distance']['text']
            ],
            'best_option' => $best
        ];
    }
}
