<?php

namespace App\Console\Commands;

use App\Activity;
use App\ActivityCategory;
use App\TourCategory;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetDataFromProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get-data-from-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from providers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function handle():void
    {
        $this->import();
    }

    private function get()
    {
        $endpoint = "https://sandbox.musement.com/api/v3/activities";
        $client = new Client();

        $response = $client->request('GET', $endpoint, ['query' => [
            'fuzziness_level'=>'LEVEL-0',
            'limit'=>100,
            'duration_range'=>1
        ]]);

        if($response->getStatusCode() == 200) {
        return json_decode($response->getBody(), true);
        }
    }

    private function import()
    {
        $data = $this->get();
        foreach ($data['data'] as $activity) {
            $activityDB = new Activity();
            $activityDB->name = $activity['title'];
            $activityDB->description = $activity['description'] ?? null;
            $activityDB->duration = $activity['duration_range']['max'];
            $activityDB->lat = $activity['latitude'] ?? null;
            $activityDB->long = $activity['longitude'] ?? null;
            $activityDB->location = $activity['city']['name'];
            $activityDB->country = $activity['city']['country']['name'];
            $activityDB->buy_price = $activity['retail_price']['value'];
            $activityDB->currency = $activity['retail_price']['currency'];
            $activityDB->image = $activity['cover_image_url'];
            $activityDB->operational_days = $activity['operational_days'] ?? 'All';
            $activityDB->rate = $activity['reviews_avg'];
            $activityDB->save();
            $categories = $activity['categories'] ?? $activity['verticals'];
            foreach ($categories as $category) {
                $getold = $this->checkCategory($category['name']);
                if(!$getold) {
                    $categoryDB = new TourCategory();
                    $categoryDB->name = $category['name'];
                    $categoryDB->save();
                }
                $activityCategory = new ActivityCategory();
                $activityCategory->activity_id = $activityDB->id;
                $activityCategory->category_id = $categoryDB->id ?? $getold->id;
                $activityCategory->save();
            }
        }
    }

    private function checkCategory($name)
    {
        return TourCategory::where('name',$name)->first();
    }

}
