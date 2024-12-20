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
        // Obtener propiedades con las reservas asociadas
        $properties = Property::with('booking')->get();

        if ($properties->isEmpty()) {
            return response()->json(['message' => 'No se encontraron propiedades'], 404);
        }

        return view('properties.index', compact('properties'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $property = Property::create(['name' => $request->name]);

        if (!$property) {
            return response()->json(['message' => 'Propiedad no creada', 'status' => 500], 500);
        }

        return response()->json(['message' => 'Propiedad creada', 'status' => 201], 201);
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $validator->errors(),
                'status' => 400,
            ], 400);
        }

        $property->update($request->all());

        return response()->json(['message' => 'Propiedad actualizada'], 200);
    }

    public function show($id)
    {
        return Property::with('booking')->findOrFail($id);
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(['message' => 'Propiedad eliminada'], 200);
    }
}
