<?php

namespace App\Http\Controllers\Api;

use App\Models\Incidence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class IncidenceController extends Controller
{

    public function index()
    {
        $incidence = Incidence::with('booking')->get();

        if ($incidence->isEmpty()) {
            return response()->json(['message' => 'No se encontraron incidentes'], 404);
        }

        $incidence = $incidence->map(function ($incidence) {
            return [
                'id' => $incidence->id,
                'name_property' => $incidence->booking->property->name,
                'name_client' => $incidence->booking->name_client,
                'description' => $incidence->description,
                'status' => $incidence->status,
            ];
        });

        return response()->json($incidence, 200);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'description' => 'required|string',
        ]);

        if ($validated->fails()) {
            $data = [
                'message' => 'Validacion fallida',
                'errors' => $validated->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $incidence = Incidence::create([
            'booking_id' => $request->booking_id,
            'description' => $request->description,
        ]);

        if (!$incidence) {
            $data = [
                'message' => 'Incidente no creado',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Incidente creado',
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $incidence = Incidence::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'description' => 'required|string',
            'status' => 'required|string',
        ]);

        if ($validated->fails()) {
            $data = [
                'message' => 'Validacion fallida',
                'errors' => $validated->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $incidence->update([
            'booking_id' => $request->booking_id,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $data = [
            'message' => 'Incidente actualizado',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function show($id)
    {
        return Incidence::findOrFail($id);
    }

    public function destroy($id)
    {
        Incidence::destroy($id);
        return response()->json(['message' => 'Incidente eliminado'], 200);
    }
}
