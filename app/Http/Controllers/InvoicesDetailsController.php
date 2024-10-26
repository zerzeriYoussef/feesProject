<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\products;
use Illuminate\Support\Facades\Storage;
use File;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {   
        $x=invoices::all();
      //  $invoices = invoices::where('id',$id)->first();
        foreach($x as $invoices){
            if($invoices->id==$id){
                break;
            }
        }
        $attachments  = invoice_attachments::where('invoice_id',$id)->get();
        $details=invoices_details::all();
        $products=products::all();

        return view('invoices.invoices_details',compact('invoices','id','details','attachments','products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // $invoices = invoice_attachments::findOrFail($request->id_file); //id_file hadhi kefach woslet awka fama code bootstrap w javascript  
       // $invoices  = invoice_attachments::where('id',$request->id_file)->get(); adhi tjib collection chbik ya5i
        $invoices = invoice_attachments::find($request->id_file);
        //$invoices->delete();
        if ($invoices) {
            // Delete the file from the public uploads disk
            Storage::disk('public_uploads')->delete($request->invoice_number . '/' . $request->file_name);
    
            // Delete the attachment record from the database
            $invoices->delete();
    
            // Flash a success message to the session
            session()->flash('delete', 'تم حذف المرفق بنجاح');
        } else {
            // Flash an error message to the session if the attachment is not found
            session()->flash('error', 'المرفق غير موجود');
        }
                
            
        
        
        return back();
    }
    public function openFile($invoice_number, $file_name)
    {
        $filePath = $invoice_number . '/' . $file_name;

        if (Storage::disk('public_uploads')->exists($filePath)) {
            $path = Storage::disk('public_uploads')->path($filePath);
            return response()->file($path);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }
    public function download($invoice_number, $file_name){
      
        $filePath = $invoice_number . '/' . $file_name;

        if (Storage::disk('public_uploads')->exists($filePath)) {
            $path = Storage::disk('public_uploads')->path($filePath);
            return response()->download( $path);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }
}

