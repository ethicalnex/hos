<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\PharmacyItem;
use App\Models\PharmacyInventory;
use App\Models\Sale;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function items(Request $request)
    {
        $items = PharmacyItem::where('hospital_id', $request->user()->hospital_id)
            ->with('inventory')
            ->orderBy('name')
            ->get();

        return response()->json($items);
    }

    public function inventory(Request $request)
    {
        $inventory = PharmacyInventory::whereHas('item', function ($query) use ($request) {
            $query->where('hospital_id', $request->user()->hospital_id);
        })->with('item')->get();

        return response()->json($inventory);
    }

    public function sales(Request $request)
    {
        $sales = Sale::where('hospital_id', $request->user()->hospital_id)
            ->with(['user', 'items.item'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($sales);
    }

    public function createSale(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:pharmacy_items,id,hospital_id,' . $request->user()->hospital_id,
            'items.*.quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $sale = Sale::create([
            'hospital_id' => $request->user()->hospital_id,
            'user_id' => $request->user()->id,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
        ]);

        foreach ($request->items as $item) {
            $sale->items()->create([
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => \App\Models\PharmacyItem::find($item['item_id'])->price,
            ]);

            // Update inventory
            $inventory = PharmacyInventory::where('item_id', $item['item_id'])->first();
            if ($inventory) {
                $inventory->decrement('quantity', $item['quantity']);
            }
        }

        return response()->json([
            'message' => 'Sale recorded successfully.',
            'sale' => $sale,
        ], 201);
    }

    public function dispensePrescription(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id,hospital_id,' . $request->user()->hospital_id,
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:pharmacy_items,id,hospital_id,' . $request->user()->hospital_id,
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Mark prescription as dispensed
        $prescription = \App\Models\Prescription::where('hospital_id', $request->user()->hospital_id)->findOrFail($request->prescription_id);
        $prescription->update(['status' => 'dispensed']);

        // Create sale
        $sale = Sale::create([
            'hospital_id' => $request->user()->hospital_id,
            'user_id' => $request->user()->id,
            'total_amount' => 0, // Calculate from items
            'payment_method' => 'prescription',
            'status' => 'completed',
        ]);

        $totalAmount = 0;

        foreach ($request->items as $item) {
            $price = \App\Models\PharmacyItem::find($item['item_id'])->price;
            $totalAmount += $price * $item['quantity'];

            $sale->items()->create([
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'price' => $price,
            ]);

            // Update inventory
            $inventory = PharmacyInventory::where('item_id', $item['item_id'])->first();
            if ($inventory) {
                $inventory->decrement('quantity', $item['quantity']);
            }
        }

        $sale->update(['total_amount' => $total Amount]);

        return response()->json([
            'message' => 'Prescription dispensed successfully.',
            'sale' => $sale,
        ], 201);
    }
}