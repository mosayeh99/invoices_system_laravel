<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Show Departments')->only(['index']);
        $this->middleware('permission:Add Departments')->only(['store']);
        $this->middleware('permission:Edit Departments')->only(['update']);
        $this->middleware('permission:Delete Departments')->only(['destroy']);
    }

    public function index()
    {
        $departments = Department::orderBy('id', 'desc')->get();
        return view('departments.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:products|max:255',
        ]);
        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => auth()->user()->name,
        ]);
        return back()->with('status', 'Department Added Successfully');
    }

    public function update(Request $request)
    {
        $departmentIsExist = Department::where('id', $request->departmentId)->exists();
        if ($departmentIsExist){
            $validated = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('departments')->ignore($request->departmentId),
                    'max:255'
                ]
            ]);
            Department::find($request->departmentId)->update([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => auth()->user()->name,
            ]);
            return back()->with('status', 'Department Updated Successfully');
        }
        return back()->with('error', 'Department Not Found');
    }

    public function destroy(Request $request)
    {
        $departmentIsExist = Department::where('id', $request->departmentId)->exists();
        if ($departmentIsExist){
            Department::destroy($request->departmentId);
            return back()->with('status', 'Department Deleted Successfully');
        }
        return back()->with('error', 'Department Not Found');
    }
}
