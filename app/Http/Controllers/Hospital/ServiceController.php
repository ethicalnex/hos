<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('hospital_id', auth()->user()->hospital_id)
            ->with('department')
            ->latest()
            ->paginate(15);
        return view('hospital.services.index', compact('services'));
    }

    public function create()
    {
        $departments = Department::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->get();
        return view('hospital.services.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:10',
        ]);

        Service::create([
            'hospital_id' => auth()->user()->hospital_id,
            'department_id' => $request->department_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'is_active' => true,
        ]);

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service created successfully!');
    }

    public function edit(Service $service)
    {
        if ($service->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        $departments = Department::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->get();
        return view('hospital.services.edit', compact('service', 'departments'));
    }

    public function update(Request $request, Service $service)
    {
        if ($service->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:10',
        ]);

        $service->update($request->only(['department_id', 'name', 'description', 'price', 'duration']));

        return redirect()->route('hospital.services.index')
            ->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        if ($service->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }
        $service->delete();
        return redirect()->route('hospital.services.index')
            ->with('success', 'Service deleted successfully!');
    }
}