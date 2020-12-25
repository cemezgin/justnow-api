<?php

namespace App\Services;

use App\ActivityBooking;
use App\Booking;
use App\TransferBooking;
use App\Utils\Helper;

class BookingService
{
    public function save($bookings)
    {
        $map = [];
        foreach ($bookings as $key => $booking) {
            $book = new ActivityBooking();
            $book->activity_id = $booking['activity_id'];
            $book->user_id = Helper::getCurrentUser();
            $book->is_used = false;
            $book->order = $key;
            $book->save();
            $map[] = $book;
        }
        return $map;
    }

    public function saveTransferBooking($activities)
    {
        $map = [];
        foreach ($activities as $key => $activity)
        {
            if($activity['is_transfer']) {
                $transfer = new TransferBooking();
                $transfer->transfer_id = Helper::getTransferProvider();
                $transfer->first_activity_id = $activity->id;
                $transfer->next_activity_id = isset($activities[$key + 1]) ? $activities[$key + 1]->id : null;
                $transfer->is_used = false;
                $transfer->save();
                $map[] = $transfer;
            }
        }
        return $map;
    }

    public function saveBooking($activityBookings, $transferBookings)
    {
        $map = [];
        $qrGroup = $this->generateRandomString();
        foreach ($activityBookings as $booking) {
            foreach ($transferBookings as $transferBooking) {
                $book = new Booking();
                $book->activity_booking_id = $booking->id;
                $book->transfer_booking_id = $transferBooking->id;
                $book->qr_code = $qrGroup;
                $book->save();
                $map[] = $book;
            }
        }
        return $map;
    }

    private function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}