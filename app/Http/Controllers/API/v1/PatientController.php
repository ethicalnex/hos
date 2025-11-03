<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = User::where('hospital_id', $request->user()->hospital_id)
            ->where('role', 'patient')
            ->select('id', 'name', 'email', 'phone', 'address')
            ->get();

        return response()->json($patients);
    }

    public function show(Request $request, $id)
    {
        $patient = User::where('hospital_id', $request->user()->hospital_id)
            ->where('role', 'patient')
            ->findOrFail($id);

        return response()->json($patient);
    }
}