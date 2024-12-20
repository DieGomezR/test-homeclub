<?php
namespace App\Http\Controllers;

use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('property')->get();
        return view('booking.index', compact('bookings'));
    }
}
