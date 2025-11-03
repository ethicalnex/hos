<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\LabOrder;
use Illuminate\Http\Request;

class LabOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = LabOrder::where('hospital_id', $request->user()->hospital_id)
            ->with(['patient', 'test'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function show(Request $request, $id)
    {
        $order = LabOrder::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . $request->user()->hospital_id,
            'test_id' => 'required|exists:lab_tests,id,hospital_id,' . $request->user()->hospital_id,
            'notes' => 'nullable<string>',
        ]);

        $order = LabOrder::create([
            'hospital_id' => $request->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'test_id' => $request->test_id,
            'status' => 'pending',
            'notes' => $request->notes,
            'ordered_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Lab order created successfully.',
            'order' => $order,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $order = LabOrder::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'result' => 'nullable<string>',
            'notes' => 'nullable<string>',
        ]);

        $order->update([
            'status' => $request->status,
            'result' => $request->result,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Lab order updated successfully.',
            'order' => $order,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $order = LabOrder::where('hospital_id', $request->user()->hospital_id)->findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Lab order deleted successfully.',
        ]);
    }
}