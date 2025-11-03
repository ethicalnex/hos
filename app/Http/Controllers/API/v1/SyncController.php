<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\LabOrder;
use App\Models\Sale;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function pull(Request $request)
    {
        $lastSync = $request->input('last_sync', '2000-01-01 00:00:00');

        $changes = [
            'appointments' => Appointment::where('hospital_id', $request->user()->hospital_id)
                ->where('updated_at', '>', $lastSync)
                ->orderBy('updated_at')
                ->get(),
            'emr' => MedicalRecord::where('hospital_id', $request->user()->hospital_id)
                ->where('updated_at', '>', $lastSync)
                ->orderBy('updated_at')
                ->get(),
            'lab_orders' => LabOrder::where('hospital_id', $request->user()->hospital_id)
                ->where('updated_at', '>', $lastSync)
                ->orderBy('updated_at')
                ->get(),
            'sales' => Sale::where('hospital_id', $request->user()->hospital_id)
                ->where('updated_at', '>', $lastSync)
                ->orderBy('updated_at')
                ->get(),
        ];

        return response()->json([
            'changes' => $changes,
            'sync_time' => now()->toDateTimeString(),
        ]);
    }

    public function push(Request $request)
    {
        $request->validate([
            'appointments' => 'nullable|array',
            'emr' => 'nullable|array',
            'lab_orders' => 'nullable|array',
            'sales' => 'nullable|array',
        ]);

        $results = [
            'appointments' => [],
            'emr' => [],
            'lab_orders' => [],
            'sales' => [],
        ];

        // Sync Appointments
        if ($request->has('appointments')) {
            foreach ($request->appointments as $appointment) {
                $record = Appointment::updateOrCreate(
                    ['id' => $appointment['id']],
                    [
                        'hospital_id' => $request->user()->hospital_id,
                        'patient_id' => $appointment['patient_id'],
                        'doctor_id' => $appointment['doctor_id'],
                        'scheduled_time' => $appointment['scheduled_time'],
                        'reason' => $appointment['reason'] ?? null,
                        'status' => $appointment['status'],
                        'notes' => $appointment['notes'] ?? null,
                    ]
                );
                $results['appointments'][] = $record;
            }
        }

        // Sync EMR
        if ($request->has('emr')) {
            foreach ($request->emr as $record) {
                $emr = MedicalRecord::updateOrCreate(
                    ['id' => $record['id']],
                    [
                        'hospital_id' => $request->user()->hospital_id,
                        'patient_id' => $record['patient_id'],
                        'doctor_id' => $record['doctor_id'] ?? $request->user()->id,
                        'diagnosis' => $record['diagnosis'],
                        'treatment_plan' => $record['treatment_plan'],
                        'symptoms' => $record['symptoms'] ?? null,
                        'temperature' => $record['temperature'] ?? null,
                        'blood_pressure' => $record['blood_pressure'] ?? null,
                        'pulse' => $record['pulse'] ?? null,
                        'respiratory_rate' => $record['respiratory_rate'] ?? null,
                        'weight' => $record['weight'] ?? null,
                        'height' => $record['height'] ?? null,
                        'doctor_notes' => $record['doctor_notes'] ?? null,
                        'nurse_notes' => $record['nurse_notes'] ?? null,
                    ]
                );
                $results['emr'][] = $emr;
            }
        }

        // Sync Lab Orders
        if ($request->has('lab_orders')) {
            foreach ($request->lab_orders as $order) {
                $labOrder = LabOrder::updateOrCreate(
                    ['id' => $order['id']],
                    [
                        'hospital_id' => $request->user()->hospital_id,
                        'patient_id' => $order['patient_id'],
                        'test_id' => $order['test_id'],
                        'status' => $order['status'],
                        'result' => $order['result'] ?? null,
                        'notes' => $order['notes'] ?? null,
                        'ordered_by' => $order['ordered_by'] ?? $request->user()->id,
                    ]
                );
                $results['lab_orders'][] = $labOrder;
            }
        }

        // Sync Sales
        if ($request->has('sales')) {
            foreach ($request->sales as $sale) {
                $newSale = Sale::updateOrCreate(
                    ['id' => $sale['id']],
                    [
                        'hospital_id' => $request->user()->hospital_id,
                        'user_id' => $sale['user_id'] ?? $request->user()->id,
                        'total_amount' => $sale['total_amount'],
                        'payment_method' => $sale['payment_method'],
                        'status' => $sale['status'],
                    ]
                );

                // Sync sale items
                if (isset($sale['items'])) {
                    $newSale->items()->delete();
                    foreach ($sale['items'] as $item) {
                        $newSale->items()->create([
                            'item_id' => $item['item_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                        ]);
                    }
                }

                $results['sales'][] = $newSale;
            }
        }

        return response()->json([
            'message' => 'Sync completed successfully.',
            'results' => $results,
        ]);
    }
}