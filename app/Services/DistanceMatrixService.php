<?php

namespace App\Services;

use App\Utils\Helper;
use GuzzleHttp\Client;

class DistanceMatrixService
{
    private const ENDPOINT = 'https://maps.googleapis.com/maps/api/distancematrix/json';

    private $coordinates1;
    private $coordinates2;
    private $type;

    public function __construct(Coordinates $coordinates1, Coordinates $coordinates2,string $type)
    {
        $this->coordinates1 = $coordinates1;
        $this->coordinates2 = $coordinates2;
        $this->type = $type;
    }

    public function curlToGoogle()
    {
        $client = new Client();
        $response = $client->request('GET', self::ENDPOINT, ['query' => [
            'destinations' => $this->coordinates1->getLatitude().','.$this->coordinates1->getLongitude(),
            'origins' => $this->coordinates2->getLatitude().','.$this->coordinates2->getLongitude(),
            'mode' => $this->type,
            'key' => env('GOOGLE_KEY')
        ]]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        return false;
    }
}
