<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{

    public function index()
    {
        $property = Property::with('booking')->get();

        if ($property->isEmpty()) {
            return response()->json(['message' => 'No se encontraron propiedades'], 404);
        }

        $property = $property->map(function ($property) {
            return [
                'id' => $property->id,
                'name' => $property->name,
            ];
        });

        return response()->json($property, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Validacion fallida',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $property = Property::create([
            'name' => $request->name,
        ]);

        if (!$property) {
            $data = [
                'message' => 'Propiedad no creada',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Propiedad creada',
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Validacion fallida',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $property->update($request->all());

        return response()->json(['message' => 'Propiedad actualizada'], 200);
    }

    public function show($id){
        return Property::findOrFail($id);
    }

    public function getBookings($id)
{
    $property = Property::with('booking')->find($id);

    if (!$property) {
        return response()->json(['message' => 'Propiedad no encontrada'], 404);
    }

    $bookings = $property->booking->map(function ($booking) {
        return [
            'id' => $booking->id,
            'name_client' => $booking->name_client,
            'date_start' => $booking->date_start,
            'date_end' => $booking->date_end,
        ];
    });

    return response()->json(['bookings' => $bookings], 200);
}

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();
        return response()->json(['message' => 'Propiedad eliminada'], 200);
    }


}
