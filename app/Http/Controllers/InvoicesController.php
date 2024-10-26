<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Models\invoices;
use App\Models\sections;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use App\Models\invoices_details;
use App\Models\products;
use App\Notifications\InvoicePaid;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $invoices = invoices::all();
        $products = products::all();
        
        return view('invoices.invoices',compact('invoices','products'));//esm dosier w file w  kel variable
    }
    public function Invoice_Paid()
    {
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('invoices.Invoice_Paid',compact('invoices'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = sections::all();    
        return view("invoices/add_invoice",compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {
         // Validate the request
       
     
         // Parse dates with Carbon
        // dd($request->invoice_Date);
         //dd($request->Due_date);
        // $invoice_Date = Carbon::createFromFormat('m/d/Y', $request->invoice_Date)->format('Y-m-d');
        //$Due_date = Carbon::createFromFormat('m/d/Y', $request->Due_date)->format('Y-m-d');
         invoices::create([
             'invoice_number' => $request->invoice_number,
             'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
             'product' => $request->product,
             'section_id' => $request->Section,
             'Amount_collection' => $request->Amount_collection,
             'Amount_Commission' => $request->Amount_Commission,
             'Discount' => $request->Discount,
             'Value_VAT' => $request->Value_VAT,
             'Rate_VAT' => $request->Rate_VAT,
             'Total' => $request->Total,
             'Status' => 'غير مدفوعة',
             'Value_Status' => 2,
             'note' => $request->note,
         ]);
         $invoices = invoices::all();
         $id = null; // Initialize $id outside the loop
         
         foreach($invoices as $x){
             $invoice_id = $x->id; // Assign the ID within the loop
         }
         
        //$invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);
        if ($request->hasFile('pic')) {//traja3 true wla false

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');// traja3 file kano mawjoud si nn null
            $file_name = $image->getClientOriginalName();// traja3 string example.jpg chouf zada tari9a ta3 $imageName louta
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments(); //ma3 save tesna3 new row 
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }
         //   $name=Auth::user()->name;
         //   $user=User::where('name',$name)->first();
          //  $user=User::first();
         //   Notification::send($user,new InvoicePaid($invoice_id));
           // $user = User::first();
           // Notification::send($user, new AddInvoice($invoice_id));
              //   $invoice_id=$request->invoice_id;
                $name=Auth::user()->name;
               $user = User::where('name',$name)->first();
               Notification::send($user, new InvoicePaid($invoice_id));
        

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    

     
         
     }
    
    /**
     * Display the specified resource.
     */
    
   public function show($id)
    {
        $invoices=invoices::where('id',$id)->first();
        return view('invoices.status_update',compact('invoices'));
    }
    public function Status_Update(invoices $invoices,$id,Request $request){
        if($request->Status=="payed"){

        
        invoices::where('id', $id)->update([
            'Status' => 'Payed',
            'Value_Status' => 1,
            'Payment_Date' => $request->Payment_Date,

        ]);
        invoices_Details::create([
            'id_Invoice' => $request->invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => $request->Status,
            'Value_Status' => 1,
            'note' => $request->note,
            'Payment_Date' => $request->Payment_Date,
            'user' => (Auth::user()->name),
        ]);
        } else if($request->Status=="Paid Partially"){
            invoices::where('id', $id)->update([
                'Status' => 'Paid Partially',
                'Value_Status' => 3,
                'Payment_Date' => $request->Payment_Date,
    
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }   
       
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {   $Id=$id;
        $invoices = invoices::where('id',$id)->first();
      //  $invoices = invoices::find($id);
      $sections = sections::all();
        return view("invoices.edit_invoice",compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices $invoices)
    {   
        $id = $request->invoice_id;
        $products=products::all();
        foreach($products as $x){
        if($request->product==$x->Product_name){
            break;

        }}
        invoices::where('id', $id)->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $x->id,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);
        session()->flash('update', 'succesfully uploaded');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
     
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();
        $id_page=$request->id_page;
        if(!$id_page){
        if($Details){
        Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);}

        $invoices->forceDelete();//fma delete ama to93ed fel db just todheherch fel tableau
        session()->flash('delete_invoice');
        return redirect('/invoices');}
        else{
            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }
    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }
    public function unpaid(){
        $invoices=invoices::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));

    }
    public function invoices_partial(){
        $invoices=invoices::where('Value_Status',3)->get();
        return view('invoices.invoices_partial',compact('invoices'));
    }
    public function Print_invoice($id){
        $invoices=invoices::where('id',$id)->first();
        
       // return $invoices;
       $products=products::all();
        return view('invoices.print',compact('invoices','products'));
    }
    public function export() 
    {
       // return "f";
        return Excel::download(new InvoicesExport, 'zarzour.xlsx'); // \ki naml hadhi 9bal 8alta iwarini 8alta lo5ra li baadeha
    }

  
}
