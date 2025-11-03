<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PharmacySale;
use App\Models\PharmacyItem;
use App\Models\PharmacyInventory;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = PharmacySale::where('hospital_id', auth()->user()->hospital_id)
            ->with(['patient', 'pharmacist'])
            ->latest()
            ->paginate(15);
        return view('pharmacy.sales.index', compact('sales'));
    }

    public function show(PharmacySale $sale)
    {
        if ($sale->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        
        return view('pharmacy.sales.show', compact('sale'));
    }

    public function create()
    {
        $patients = \App\Models\User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', 'patient')
            ->where('is_active', true)
            ->get();
            
        $items = \App\Models\PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->get();
            
        return view('pharmacy.sales.create', compact('patients', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id,role,patient,hospital_id,' . auth()->user()->hospital_id,
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:pharmacy_items,id,hospital_id,' . auth()->user()->hospital_id,
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $totalAmount = 0;
        $itemsData = [];
        
        foreach ($request->items as $itemData) {
            $item = \App\Models\PharmacyItem::find($itemData['item_id']);
            $totalAmount += $item->price * $itemData['quantity'];
            
            $itemsData[] = [
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
                'price' => $item->price,
                'total' => $item->price * $itemData['quantity'],
            ];
        }

        $sale = PharmacySale::create([
            'hospital_id' => auth()->user()->hospital_id,
            'patient_id' => $request->patient_id,
            'pharmacist_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'notes' => $request->notes,
        ]);

        // Update inventory
        foreach ($itemsData as $itemData) {
            $item = \App\Models\PharmacyItem::find($itemData['item_id']);
            $inventory = $item->inventory;
            
            if ($inventory && $inventory->quantity >= $itemData['quantity']) {
                $inventory->update([
                    'quantity' => $inventory->quantity - $itemData['quantity'],
                ]);
            }
        }

        return redirect()->route('pharmacy.sales.index')
            ->with('success', 'Sale completed successfully!');
    }
}