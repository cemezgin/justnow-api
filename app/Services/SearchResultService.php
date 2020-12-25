<?php

namespace App\Services;

use App\Utils\Helper;
use Illuminate\Support\Facades\DB;

class SearchResultService
{
    private $location;
    private $country;
    private $currentGeolocation;

    public function __construct(
        string $location,
        string $country,
        array $currentGeolocation
    ){
        $this->location = $location;
        $this->country = $country;
        $this->currentGeolocation = $currentGeolocation;
    }

    private function filterByPreviousInterest()
    {
        $userId = Helper::getCurrentUser();
        return DB::connection('pgsql')
            ->select(" select DISTINCT tour_categories.id,count(tour_categories.id) FROM activity_bookings
                                 inner join activities on activities.id = activity_bookings.activity_id
                                 inner join activity_categories on activities.id = activity_bookings.activity_id
                                 inner join tour_categories on category_id = tour_categories.id
                                 WHERE activity_categories.activity_id = activities.id
                                  and activity_bookings.user_id = $userId GROUP BY tour_categories.id order by count;");
    }

    private function getFirstBookingInLocation()
    {
        return DB::connection('pgsql')
            ->select(" select DISTINCT tour_categories.id,count(tour_categories.id) FROM activity_bookings
                                 inner join activities on activities.id = activity_bookings.activity_id
                                 inner join activity_categories on activities.id = activity_bookings.activity_id
                                 inner join tour_categories on category_id = tour_categories.id
                                 WHERE activity_categories.activity_id = activities.id
                                  and activity_bookings.is_first = true and activities.country = '$this->country' GROUP BY tour_categories.id order by count;");
    }

    private function filterByPopularInLocation()
    {
        return DB::connection('pgsql')
            ->select(" select DISTINCT tour_categories.id,count(tour_categories.id) FROM activity_bookings
                                 inner join activities on activities.id = activity_bookings.activity_id
                                 inner join activity_categories on activities.id = activity_bookings.activity_id
                                 inner join tour_categories on category_id = tour_categories.id
                                 WHERE activity_categories.activity_id = activities.id
                                  and activities.country = '$this->country' GROUP BY tour_categories.id order by count;");
    }

    private function getAllCategories()
    {
        return DB::connection('pgsql')
            ->select(" select DISTINCT tour_categories.id FROM activity_categories
                                 inner join activities on activities.id = activity_categories.activity_id
                                 inner join tour_categories on activity_categories.category_id = tour_categories.id
                                 WHERE activity_categories.activity_id = activities.id
                                  and activities.country = '$this->country';");
    }

    private function getByCountryAndInterests()
    {
        $categoryIds = $this->categories();

        $map = '';
        foreach ($categoryIds as $categoryId) {
            $map .= $categoryId->id.',';
        }

        if ($map != '') {
            $map = substr($map, 0, -1);
        }

        return DB::connection('pgsql')
            ->select(" select
                            activities.name,description,duration,lat,long,location,country,buy_price,currency,start_time,end_time,image,operational_days,rate,activity_id
                            FROM activities
                            inner join activity_categories on activities.id = activity_categories.activity_id
                            where country='$this->country' and activity_categories.category_id IN ($map);");
    }

    private function categories()
    {
        if (empty($this->filterByPreviousInterest())) {
            $interests = $this->getFirstBookingInLocation();
        } else {
            $interests = $this->filterByPreviousInterest();
        }
        $interests = count($interests) < 10 ? array_merge($interests, $this->filterByPopularInLocation()) : $interests;
        $interests = count($interests) < 10 ? array_merge($interests, $this->getAllCategories()) : $interests;


        $input = array_map("unserialize", array_unique(array_map("serialize", $interests)));

        return $input;
    }

    public function exec()
    {
        $geoLocations = [];

        $currentLocation = new Coordinates($this->currentGeolocation['latitude'], $this->currentGeolocation['longitude']);

        $input = array_map("unserialize", array_unique(array_map("serialize", $this->getByCountryAndInterests())));

        foreach ($input as $activities) {
            $geoLocations[] = [
                'distance' => Helper::distanceBetweenGeoLocations(
                    $currentLocation,
                    new Coordinates($activities->lat, $activities->long)
                ),
                'activity' => $activities
            ];
        }

        usort($geoLocations, function ($a, $b) {
            return strcmp($a["distance"], $b["distance"]);
        });

        return $geoLocations;
    }

}
