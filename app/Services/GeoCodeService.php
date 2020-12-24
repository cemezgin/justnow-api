<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeoCodeService
{
    private const ENDPOINT = 'https://maps.googleapis.com/maps/api/geocode/json';

    private $location;
    private $country;

    public function __construct(string $location, string $country)
    {
        $this->location = $location;
        $this->country = $country;
    }

    private function curl()
    {
        $client = new Client();

        $response = $client->request('GET', self::ENDPOINT, ['query' => [
            'address' => sprintf('%s,$s', [$this->location, $this->country]),
            'key' => env('GOOGLE_KEY')
        ]]);

        if($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        return false;
    }

    public function getGeolocation()
    {
        return $this->curl()['results']['geometry']['location'];
    }
}
