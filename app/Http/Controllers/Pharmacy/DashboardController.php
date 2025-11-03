<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PharmacyItem;
use App\Models\PharmacySale;
use App\Models\PharmacyInventory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hospital = auth()->user()->hospital;
        
        if (!$hospital->hasFeature('pharmacy')) {
            return redirect()->route('pharmacy.dashboard')->with('error', 'Pharmacy feature is not available in your current plan.');
        }

        $pendingPrescriptions = Prescription::where('hospital_id', auth()->user()->hospital_id)
            ->where('status', 'pending')
            ->count();
            
        $todaySales = PharmacySale::where('hospital_id', auth()->user()->hospital_id)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount');
            
        $lowStockItems = PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
            ->whereHas('inventory', function ($q) {
                $q->where('quantity', '<=', 10);
            })
            ->with('inventory')
            ->get();
            
        $totalItems = PharmacyItem::where('hospital_id', auth()->user()->hospital_id)->count();
        
        return view('pharmacy.dashboard', compact(
            'pendingPrescriptions', 'todaySales', 'lowStockItems', 'totalItems'
        ));
    }
}