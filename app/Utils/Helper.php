<?php

namespace App\Utils;

use App\Services\Coordinates;

class Helper
{
    public static function getCurrentUser()
    {
        return 1;
    }

    public static function getTransferProvider()
    {
        return 1;
    }

    /**
     * Haversine formula:
     * a = sin²(Δφ/2) + cos φ1 ⋅ cos φ2 ⋅ sin²(Δλ/2)
     * c = 2 ⋅ atan2( √a, √(1−a) )
     * d = R ⋅ c.
     *
     * where
     * φ is latitude, λ is longitude, R is earth’s radius (meaning radius = 6,371km);
     * note that angles need to be in radians.
     *
     * @param Coordinates $coordinate1
     * @param Coordinates $coordinate2
     *
     * @return float
     */
    public static function distanceBetweenGeoLocations(Coordinates $coordinate1, Coordinates $coordinate2): float
    {
        $latitude1 = $coordinate1->getLatitude();
        $latitude2 = $coordinate2->getLatitude();
        $longitude1 = $coordinate1->getLongitude();
        $longitude2 = $coordinate2->getLongitude();

        $earthRadius = 6371;

        $latDelta = \deg2rad($latitude2 - $latitude1);
        $lngDelta = \deg2rad($longitude2 - $longitude1);

        return
            $earthRadius
            * 2
            * \asin(
                \sqrt(
                    \sin($latDelta / 2) * \sin($latDelta / 2)
                    +
                    \cos(\deg2rad($latitude1)) * \cos(\deg2rad($latitude2)) * \sin($lngDelta / 2) * \sin($lngDelta / 2)
                )
            );
    }
}
