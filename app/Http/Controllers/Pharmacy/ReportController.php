<?php
namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\PharmacyReport;
use App\Models\Prescription;
use App\Models\PharmacySale;
use App\Models\PharmacyItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $reports = PharmacyReport::where('hospital_id', auth()->user()->hospital_id)
            ->latest()
            ->paginate(15);
        return view('pharmacy.reports.index', compact('reports'));
    }

    public function generate()
    {
        return view('pharmacy.reports.generate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,inventory',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $data = [];

        switch ($request->report_type) {
            case 'daily':
                $sales = PharmacySale::where('hospital_id', auth()->user()->hospital_id)
                    ->whereDate('created_at', $request->start_date)
                    ->get();
                $data = [
                    'type' => 'Daily',
                    'date' => $request->start_date,
                    'total_sales' => $sales->sum('total_amount'),
                    'sales_count' => $sales->count(),
                    'items_sold' => $sales->sum(function ($sale) {
                        return $sale->items->sum('quantity') ?? 0;
                    }),
                    'sales' => $sales->toArray(),
                ];
                break;
                
            case 'weekly':
                $sales = PharmacySale::where('hospital_id', auth()->user()->hospital_id)
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->get();
                $data = [
                    'type' => 'Weekly',
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'total_sales' => $sales->sum('total_amount'),
                    'sales_count' => $sales->count(),
                    'items_sold' => $sales->sum(function ($sale) {
                        return $sale->items->sum('quantity') ?? 0;
                    }),
                    'sales' => $sales->toArray(),
                ];
                break;
                
            case 'monthly':
                $sales = PharmacySale::where('hospital_id', auth()->user()->hospital_id)
                    ->whereYear('created_at', date('Y', strtotime($request->start_date)))
                    ->whereMonth('created_at', date('m', strtotime($request->start_date)))
                    ->get();
                $data = [
                    'type' => 'Monthly',
                    'month' => date('F Y', strtotime($request->start_date)),
                    'total_sales' => $sales->sum('total_amount'),
                    'sales_count' => $sales->count(),
                    'items_sold' => $sales->sum(function ($sale) {
                        return $sale->items->sum('quantity') ?? 0;
                    }),
                    'sales' => $sales->toArray(),
                ];
                break;
                
            case 'inventory':
                $items = PharmacyItem::where('hospital_id', auth()->user()->hospital_id)
                    ->with('inventory')
                    ->get();
                $data = [
                    'type' => 'Inventory',
                    'total_items' => $items->count(),
                    'low_stock' => $items->filter(function ($item) {
                        return $item->available_quantity <= 10;
                    })->count(),
                    'out_of_stock' => $items->filter(function ($item) {
                        return $item->available_quantity == 0;
                    })->count(),
                    'items' => $items->map(function ($item) {
                        return [
                            'name' => $item->name,
                            'category' => $item->category,
                            'form' => $item->form,
                            'strength' => $item->strength,
                            'price' => $item->price,
                            'available_quantity' => $item->available_quantity,
                            'expiry_date' => $item->inventory?->expiry_date ?? '—',
                            'batch_number' => $item->inventory?->batch_number ?? '—',
                        ];
                    })->toArray(),
                ];
                break;
        }

        // Generate PDF
        $pdf = Pdf::loadView('pharmacy.reports.pdf', compact('data', 'request'));
        $filename = 'pharmacy-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        $path = 'pharmacy-reports/' . $filename;
        \Storage::put('public/' . $path, $pdf->output());

        // Save report
        PharmacyReport::create([
            'hospital_id' => auth()->user()->hospital_id,
            'report_type' => $request->report_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'data' => $data,
            'generated_by' => auth()->user()->name,
            'report_path' => $path,
            'is_shared_with_admin' => false,
        ]);

        return redirect()->route('pharmacy.reports.index')
            ->with('success', 'Report generated successfully!');
    }

    public function download(PharmacyReport $report)
    {
        if ($report->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        
        return response()->file(storage_path('app/public/' . $report->report_path));
    }
}