<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function saveBooking(Request $request)
    {
        $activities = $request->get('activities');

        $bookActivity = $this->bookingService->save($activities);
        $bookTransfer = $this->bookingService->saveTransferBooking($activities);
        $save = $this->bookingService->saveBooking($bookActivity,$bookTransfer);

        return response()->json($save);
    }

    public function useBooking(Request $request) {
        $result = $this->bookingService->useBooking($request->get('qr_code'), $request->get('activity_booking_id'),  $request->get('transfer_booking_id'));
        return response()->json($result);
    }
}
