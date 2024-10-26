<?php

namespace App\Http\Controllers;

//use App\Models\Product;
use App\Models\products;
use App\Models\Section;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = sections::all();
        $products = products::all();

        return view('products.products', compact('sections', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validating the incoming request
        $validatedData = $request->validate([
            'Product_name' => 'required|unique:products|max:255',
            'section_id' => 'required|exists:sections,id',
            'description' => 'required',
        ], [
            // Custom error messages
            'Product_name.required' => 'Product name is required',
            'Product_name.unique' => 'Product name already exists',
            'Product_name.max' => 'Product name should be under 255 characters',
            'section_id.required' => 'Section is required',
            'section_id.exists' => 'Selected section does not exist',
            'description.required' => 'Description is required',
        ]);

        products::create([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);

        session()->flash('Add', 'Product has been successfully added');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function show(products $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(products $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, products $product)
{
    $validatedData = $request->validate([
        'Product_name' => 'required|unique:products,Product_name,' . $product->id . '|max:255',
        'description' => 'required',
    ], [
        'Product_name.required' => 'Product name is required',
        'Product_name.unique' => 'Product name has already been taken',
        'Product_name.max' => 'Product name should not exceed 255 characters',
        'description.required' => 'Description is required',
    ]);

    $product->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
    ]);

    session()->flash('edit', 'Product has been successfully updated');
    return redirect('/products');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(products $product)
    {
       // return $product;
        
        $product->delete();
        session()->flash('delete', 'Product has been successfully deleted');
        return redirect('/products');
        
    }
}
