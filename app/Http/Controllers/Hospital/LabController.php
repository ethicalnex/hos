<?php
namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\LabTestCategory;
use App\Models\LabTest;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function categoriesIndex()
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        $categories = \App\Models\LabCategory::where('hospital_id', auth()->user()->hospital_id)->latest()->paginate(15);
        return view('hospital.lab.categories.index', compact('categories'));
    }

    public function categoriesCreate()
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        return view('hospital.lab.categories.create');
    }

    public function categoriesStore(\Illuminate\Http\Request $request)
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable<string>',
            'is_active' => 'boolean',
        ]);

        $category = \App\Models\LabCategory::create([
            'hospital_id' => auth()->user()->hospital_id,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('hospital.lab.categories.index')
            ->with('success', 'Lab category created successfully!');
    }

    public function categoriesEdit(\App\Models\LabCategory $category)
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        if ($category->hospital_id !== auth()->user()->hospital_id) abort(403);
        return view('hospital.lab.categories.edit', compact('category'));
    }

    public function categoriesUpdate(\Illuminate\Http\Request $request, \App\Models\LabCategory $category)
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        if ($category->hospital_id !== auth()->user()->hospital_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable<string>',
            'is_active' => 'boolean',
        ]);

        $category->update($request->only(['name', 'description', 'is_active']));

        return redirect()->route('hospital.lab.categories.index')
            ->with('success', 'Lab category updated successfully!');
    }

    public function testsIndex()
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        $tests = LabTest::where('hospital_id', auth()->user()->hospital_id)
            ->with('category')
            ->latest()
            ->paginate(15);
        return view('hospital.lab.tests.index', compact('tests'));
    }

    public function testsCreate()
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        $categories = LabCategory::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->get();
        return view('hospital.lab.tests.create', compact('categories'));
    }

    public function testsStore(\Illuminate\Http\Request $request)
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        $request->validate([
            'category_id' => 'required|exists:lab_categories,id,hospital_id,' . auth()->user()->hospital_id,
            'name' => 'required|string|max:255',
            'description' => 'nullable<string>',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $test = LabTest::create([
            'hospital_id' => auth()->user()->hospital_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('hospital.lab.tests.index')
            ->with('success', 'Lab test created successfully!');
    }

    public function testsEdit(LabTest $test)
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        if ($test->hospital_id !== auth()->user()->hospital_id) abort(403);
        $categories = LabCategory::where('hospital_id', auth()->user()->hospital_id)
            ->where('is_active', true)
            ->get();
        return view('hospital.lab.tests.edit', compact('test', 'categories'));
    }

    public function testsUpdate(\Illuminate\Http\Request $request, LabTest $test)
    {
        $result = \App\Helpers\FeatureHelper::check('lab');
        if ($result) return $result;

        if ($test->hospital_id !== auth()->user()->hospital_id) abort(403);

        $request->validate([
            'category_id' => 'required|exists:lab_categories,id,hospital_id,' . auth()->user()->hospital_id,
            'name' => 'required|string|max:255',
            'description' => 'nullable<string>',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $test->update($request->only(['category_id', 'name', 'description', 'price', 'is_active']));

        return redirect()->route('hospital.lab.tests.index')
            ->with('success', 'Lab test updated successfully!');
    }
}