<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\PharmacyItem;
use App\Models\PharmacyInventory;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    public function itemsIndex()
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        $items = PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
            ->with('inventory')
            ->latest()
            ->paginate(15);
        return view('hospital.pharmacy.items.index', compact('items'));
    }

    public function itemsCreate()
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        return view('hospital.pharmacy.items.create');
    }

    public function itemsStore(\Illuminate\Http\Request $request)
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable<string>',
            'category' => 'required<string>',
            'form' => 'required<string>',
            'strength' => 'required<string>',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $item = PharmacyItem::create([
            'hospital_id' => auth()->user()->hospital_id,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'form' => $request->form,
            'strength' => $request->strength,
            'price' => $request->price,
            'is_active' => $request->is_active,
        ]);

        // Create initial inventory record
        PharmacyInventory::create([
            'hospital_id' => auth()->user()->hospital_id,
            'item_id' => $item->id,
            'quantity' => 0,
            'expiry_date' => null,
            'batch_number' => null,
        ]);

        return redirect()->route('hospital.pharmacy.items.index')
            ->with('success', 'Pharmacy item created successfully!');
    }

    public function itemsEdit(PharmacyItem $item)
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        if ($item->hospital_id !== auth()->user()->hospital_id) abort(403);
        return view('hospital.pharmacy.items.edit', compact('item'));
    }

    public function itemsUpdate(\Illuminate\Http\Request $request, PharmacyItem $item)
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        if ($item->hospital_id !== auth()->user()->hospital_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable<string>',
            'category' => 'required<string>',
            'form' => 'required<string>',
            'strength' => 'required<string>',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $item->update($request->only(['name', 'description', 'category', 'form', 'strength', 'price', 'is_active']));

        return redirect()->route('hospital.pharmacy.items.index')
            ->with('success', 'Pharmacy item updated successfully!');
    }

    public function inventoryIndex()
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        $items = PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
            ->with('inventory')
            ->latest()
            ->paginate(15);
        return view('hospital.pharmacy.inventory.index', compact('items'));
    }

    public function inventoryAdd(PharmacyItem $item)
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        if ($item->hospital_id !== auth()->user()->hospital_id) abort(403);
        return view('hospital.pharmacy.inventory.add', compact('item'));
    }

    public function inventoryStore(\Illuminate\Http\Request $request, PharmacyItem $item)
    {
        $result = \App\Helpers\FeatureHelper::check('pharmacy');
        if ($result) return $result;

        if ($item->hospital_id !== auth()->user()->hospital_id) abort(403);

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'expiry_date' => 'nullable<date>',
            'batch_number' => 'nullable<string>',
        ]);

        $inventory = $item->inventory;
        
        if (!$inventory) {
            $inventory = PharmacyInventory::create([
                'hospital_id' => auth()->user()->hospital_id,
                'item_id' => $item->id,
                'quantity' => 0,
                'expiry_date' => null,
                'batch_number' => null,
            ]);
        }

        $inventory->update([
            'quantity' => $inventory->quantity + $request->quantity,
            'expiry_date' => $request->expiry_date,
            'batch_number' => $request->batch_number,
        ]);

        return redirect()->route('hospital.pharmacy.inventory.index')
            ->with('success', 'Inventory updated successfully!');
    }
}