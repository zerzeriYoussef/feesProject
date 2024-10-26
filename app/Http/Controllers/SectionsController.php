<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $sections =sections::all();//select all from table...

        return view('sections.sections',compact('sections'));//
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
        /*$input =$request->all();
        $b_exists =sections::where('section_name','=',$input['section_name'])->exists();
        if($b_exists){
            session()->flash('Error','class has been already stored');
            return redirect('/sections');
        }
        else{
            sections::create(['section_name'=>$request->section_name,'description'=>$request->description,'Created_by'=>(Auth::user()->name)]);
            session()->flash('Add','The section has been added successfully');
            return redirect('/sections');

        }*/ // 5admet wahda bark erreur meloul
        $validatedData = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
        ],[

            'section_name.required' =>'you should write the section name',
            'section_name.unique' =>'section name has been already used',
            'description.required' =>'you should write the description',


        ]);

            sections::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'Created_by' => (Auth::user()->name),

            ]);
            session()->flash('Add', 'succesfully added');
            return redirect('/sections');
    }



    /**
     * Display the specified resource.
     */
    
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id, // KAN el esm houa bido fel colone aki 5alih mouch erreur manaha nheb nbdel kan description
            'description' => 'required',
        ],[

            'section_name.required' =>'you should write the section name',
            'section_name.unique' =>'section name has been already used',
            'description.required' =>'you should write the description',

        ]);

        $sections = sections::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','section has been succesfully updated');
        return redirect('/sections');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('delete','we have succesfully deleted the column');
        return redirect('/sections');
    }
    public function getProductsBySection($sectionId)
    {
        $products = products::where('section_id', $sectionId)->pluck('name', 'id');
        return response()->json($products);
    }

}
