<?php
namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmrController extends Controller
{
    public function editNotes(MedicalRecord $record): View
    {
        if ($record->patient->hospital_id !== auth()->user()->hospital_id) abort(403);
        return view('nurse.emr.notes', compact('record'));
    }

    public function updateNotes(Request $request, MedicalRecord $record): RedirectResponse
    {
        if ($record->patient->hospital_id !== auth()->user()->hospital_id) abort(403);
        $record->update(['nurse_id' => auth()->id(), 'nurse_notes' => $request->nurse_notes]);
        return back()->with('success', 'Nurse notes updated!');
    }
}