<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Department;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Show Products')->only(['index']);
        $this->middleware('permission:Add Products')->only(['store']);
        $this->middleware('permission:Edit Products')->only(['update']);
        $this->middleware('permission:Delete Products')->only(['destroy']);
    }

    public function index()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $departments = Department::orderBy('id', 'desc')->get();
        return view('products.products', compact('products', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:products|max:255',
            'department_id' => 'required',
        ],[
            'department_id.required' => 'The department field is required.'
        ]);
        $isProductExist = Product::where('name', $request->name)->exists();
        if ($isProductExist){
            return back()->with('error', 'Product Is Already Exist');
        }
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department_id,
        ]);
        return back()->with('status', 'Product Created Successfully');
    }

    public function update(Request $request)
    {
        $isDepartmentExist = Department::where('name', $request->department_name)->exists();
        $isProductExist = Product::where('id', $request->product_id)->exists();
        if ($isDepartmentExist && $isProductExist) {
            $validated = $request->validate([
                'name' => [
                    'required',
                    Rule::unique('products')->ignore($request->product_id),
                    'max:255'
                ],
                'department_name' => 'required',
            ]);
            $departmentId = Department::where('name', $request->department_name)->first()->id;
            Product::find($request->product_id)->update([
                "name" => $request->name,
                "department_id" => $departmentId,
                "description" => $request->description
            ]);
            return back()->with('status', 'Product Updated Successfully');
        }
        return back()->with('error', 'Product Not Found');
    }

    public function destroy(Request $request)
    {
        $isProductExist = Product::where('id', $request->product_id)->exists();
        if ($isProductExist) {
            Product::destroy($request->product_id);
            return back()->with('status', 'Product Deleted Successfully');
        }
        return back()->with('error', 'Product Not Found');
    }

    public function show($department_id)
    {
        $isDepartmentExist = Department::where('id', $department_id)->exists();
        if ($isDepartmentExist){
            $department = Department::find($department_id);
            $products = ProductResource::collection($department->products);
            return response($products, 200);
        }
    }
}
