<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Storage;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\invoices_details;
use App\Models\products;

class InvoicesArchiveController extends Controller{
    public function index(){
        $invoices=invoices::onlyTrashed()->get();//eli tfas5o khw w 9a3do fel db kel delet 3adiya
        return view('invoices.ArchiveInvoices',compact('invoices'));
        
    }
    public function destroy($id)
    {

    
        $invoices = invoices::where('id', $id)->first();
    
        // Check if $invoices is null
        if (!$invoices) {
            // Handle the case where invoice with given id is not found
            session()->flash('found');
            return redirect('/archive');
        }
        
    
        // Proceed to delete related attachments if they exist
        $Details = invoice_attachments::where('invoice_id', $id)->first();
        if ($Details) {
            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }
    
        // Now attempt to force delete the invoice
        $invoices->forceDelete();
    
        session()->flash('delete_invoice');
        return redirect('/archive');
    }
    
    public function update(Request $request)
    {
         $id = $request->invoice_id;
         $flight = Invoices::withTrashed()->where('id', $id)->restore();
         session()->flash('restore_invoice');
         return redirect('/invoices');
    }
    


}