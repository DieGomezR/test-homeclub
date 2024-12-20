<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Booking::with('property')->get(); // Cargar la relación property

        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'No existen reservas'], 404);
        }

        // Modificar la respuesta para incluir 'name_property'
        $bookings = $bookings->map(function ($booking) {
            return [
                'id' => $booking->id,
                'name_client' => $booking->name_client,
                'date_start' => $booking->date_start,
                'date_end' => $booking->date_end,
                'name_property' => $booking->property->name, // Obtener el nombre de la propiedad
            ];
        });

        return response()->json($bookings, 200);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'name_client' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start'
        ]);

        if ($validated->fails()) {
            $data = [
                'message' => 'Validacion fallida',
                'errors' => $validated->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $availability = Booking::where('property_id', $request->property_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_start', [$request['date_start'], $request['date_end']])
                    ->orWhereBetween('date_end', [$request['date_start'], $request['date_end']]);
            })
            ->exists();

        if ($availability) {
            return response()->json(['message' => 'Ya existe una reserva en las fechas seleccionadas']);
        }

        $booking = Booking::create([
            'property_id' => $request->property_id,
            'name_client' => $request->name_client,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end
        ]);

        if (!$booking) {
            $data = [
                'message' => 'Reserva no creada',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Reserva creada',
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'property_id' => 'required|exists:properties,id',
            'name_client' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start'
        ]);

        if ($validated->fails()) {
            $data = [
                'message' => 'Validacion fallida',
                'errors' => $validated->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $availability = Booking::where('property_id', $request->property_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('date_start', [$request['date_start'], $request['date_end']])
                    ->orWhereBetween('date_end', [$request['date_start'], $request['date_end']]);
            })
            ->where('id', '!=', $id)
            ->exists();

        if ($availability) {
            return response()->json(['icon' => 'error', 'title' => 'Error', 'message' => 'Ya existe una reserva en las fechas seleccionadas']);
        }

        $booking->update($request->all());

        return response()->json(['message' => 'Reserva actualizada'], 200);
    }

    public function show($id)
    {
        return Booking::findOrFail($id);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return response()->json(['message' => 'Reserva eliminada'], 200);
    }
}
