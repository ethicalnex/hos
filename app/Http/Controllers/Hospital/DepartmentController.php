<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with(['headDoctor'])
            ->where('hospital_id', auth()->user()->hospital_id)
            ->latest()
            ->paginate(15);

        return view('hospital.departments.index', compact('departments'));
    }

    public function create()
    {
        $doctors = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', User::ROLE_DOCTOR)
            ->where('is_active', true)
            ->get();

        return view('hospital.departments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_doctor_id' => 'nullable|exists:users,id',
        ]);

        try {
            Department::create([
                'hospital_id' => auth()->user()->hospital_id,
                'name' => $request->name,
                'description' => $request->description,
                'head_doctor_id' => $request->head_doctor_id,
                'is_active' => true,
            ]);

            return redirect()->route('hospital.departments.index')
                ->with('success', 'Department created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create department: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Department $department)
    {
        // Ensure the department belongs to the same hospital
        if ($department->hospital_id !== auth()->user()->hospital_id) {
            abort(403, 'Unauthorized action.');
        }

        $doctors = User::where('hospital_id', auth()->user()->hospital_id)
            ->where('role', User::ROLE_DOCTOR)
            ->where('is_active', true)
            ->get();

        return view('hospital.departments.edit', compact('department', 'doctors'));
    }

    public function update(Request $request, Department $department)
    {
        if ($department->hospital_id !== auth()->user()->hospital_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_doctor_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $department->update($request->only([
            'name', 'description', 'head_doctor_id', 'is_active'
        ]));

        return redirect()->route('hospital.departments.index')
            ->with('success', 'Department updated successfully!');
    }
}