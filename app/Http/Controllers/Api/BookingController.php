<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function book(string $id, Request $request)
    {
        try {
            $booking = DB::transaction(function () use ($id, $request) {
                // Lock event for update to prevent race conditions
                $event = Event::lockForUpdate()->find($id);

                if (!$event) {
                    return null;
                }

                // Check if event is full
                if ($event->seats_taken >= $event->capacity) {
                    return false; // Signal that event is full
                }

                // Check for existing booking
                $existingBooking = Booking::where('user_id', $request->user()->id)
                    ->where('event_id', $id)
                    ->first();

                if ($existingBooking) {
                    return 'duplicate'; // Signal duplicate booking
                }

                // Create booking
                $booking = Booking::create([
                    'user_id' => $request->user()->id,
                    'event_id' => $id,
                    'created_at' => now(),
                ]);

                // Increment seats_taken
                $event->increment('seats_taken');

                return $booking;
            });

            if ($booking === null) {
                return response()->json(['message' => 'Event not found'], 404);
            }

            if ($booking === false) {
                return response()->json(['message' => 'Event is full'], 409);
            }

            if ($booking === 'duplicate') {
                return response()->json(['message' => 'You have already booked this event'], 422);
            }

            return response()->json($booking, 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle unique constraint violation
            if ($e->getCode() == 23000) {
                return response()->json(['message' => 'You have already booked this event'], 422);
            }

            throw $e;
        }
    }

    public function userBookings(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bookings);
    }

}