<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\PharmacyInventory;
use Illuminate\Http\Request;

class DispenseController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::where('hospital_id', auth()->user()->hospital_id)
            ->where('status', 'pending')
            ->with(['patient', 'doctor', 'items.item'])
            ->latest()
            ->paginate(15);
        return view('pharmacy.dispense.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        if ($prescription->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        
        return view('pharmacy.dispense.show', compact('prescription'));
    }

    public function dispense(Request $request, Prescription $prescription)
    {
        if ($prescription->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:pharmacy_items,id,hospital_id,' . auth()->user()->hospital_id,
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Check inventory before dispensing
        foreach ($request->items as $itemData) {
            $item = \App\Models\PharmacyItem::find($itemData['item_id']);
            $inventory = $item->inventory;
            
            if (!$inventory || $inventory->quantity < $itemData['quantity']) {
                return back()->withErrors(['error' => 'Not enough stock for ' . $item->name]);
            }
        }

        // Update prescription status and items
        $prescription->update([
            'status' => 'dispensed',
            'pharmacist_id' => auth()->id(),
        ]);

        foreach ($request->items as $itemData) {
            $item = \App\Models\PharmacyItem::find($itemData['item_id']);
            $inventory = $item->inventory;
            
            $inventory->update([
                'quantity' => $inventory->quantity - $itemData['quantity'],
            ]);

            $prescriptionItem = $prescription->items()->where('item_id', $itemData['item_id'])->first();
            if ($prescriptionItem) {
                $prescriptionItem->update(['is_dispensed' => true]);
            }
        }

        return redirect()->route('pharmacy.dispense.index')
            ->with('success', 'Prescription dispensed successfully!');
    }
}