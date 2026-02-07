<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('starts_at', '>=', Carbon::now())
            ->orderBy('starts_at', 'asc')
            ->get();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'starts_at' => 'required|date_format:Y-m-d H:i:s|after:now',
            'capacity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event = Event::create($request->only('title', 'starts_at', 'capacity'));

        return response()->json($event, 201);
    }

    public function show(string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event);
    }

    public function update(Request $request, string $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'starts_at' => 'sometimes|date_format:Y-m-d H:i:s|after:now',
            'capacity' => 'sometimes|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $event->update($request->only('title', 'starts_at', 'capacity'));

        return response()->json($event);
    }

    public function destroy(string $id, Request $request)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(["status" => "success", "message" => "Event deleted successfully"]);
    }
}
