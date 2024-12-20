<?php

namespace App\Http\Controllers;

use App\Models\Incidence;

class IncidenceController extends Controller
{
    public function index()
    {
        $incidences = Incidence::all();
        return view('incidence.index', compact('incidences'));
    }
}
