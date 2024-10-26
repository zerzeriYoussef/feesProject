<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicesArchiveController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvoicePaid;
use App\Models\User;
use Spatie\Permission\Middlewares\RoleMiddleware; 
Route::get('/', function () {
    return view('auth.login');
});

// Authentication Routes
Auth::routes();
Route::get('/export_invoices', [InvoicesController::class, 'export']);

Route::middleware(['auth'])->group(function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Invoices Routes
Route::get('invoices', [InvoicesController::class, 'index'])->name('index');
Route::get('invoices/add_invoice', [InvoicesController::class, 'create'])->name('create');
Route::post('invoices/store', [InvoicesController::class, 'store'])->name('store');

// Sections and Products Routes
Route::resource('sections', SectionsController::class);
Route::resource('products', ProductsController::class);

// Additional Routes
Route::get('/section/{id}', [InvoicesController::class, 'getproducts']);
Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'edit']);
Route::get('/InvoicesDetails/openfile/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'openFile']);
Route::get('download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'download']);
Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('delete_file');
Route::get('/edit_invoice/{id}', [InvoicesController::class, 'edit']);
Route::get('invoices/update', [InvoicesController::class, 'update']);
Route::delete('delete_file', [InvoicesController::class, 'destroy'])->name('delete_file');
Route::get('/Status_show/{id}', [InvoicesController::class, 'show'])->name('Status_show');
Route::post('/Status_Update/{id}', [InvoicesController::class, 'Status_Update'])->name('Status_Update');
Route::get('invoices/Invoice_Paid', [InvoicesController::class, 'Invoice_Paid'])->name('Invoice_Paid');
// Archive Routes
Route::resource('archive', InvoicesArchiveController::class);

// Additional Invoice Routes
Route::get('invoices/unpaid', [InvoicesController::class, 'unpaid'])->name('unpaid');
Route::get('invoices/partial', [InvoicesController::class, 'invoices_partial'])->name('partial');
Route::delete('/archive/{id}', [InvoicesArchiveController::class, 'destroy'])->name('destro');

Route::get('/Print_invoice/{id}', [InvoicesController::class, 'Print_invoice']);

// Default Route
Route::get('/{page}', [AdminController::class, 'index']);


Route::post('/print-and-save', [InvoicesController::class, 'printAndSave'])->name('print-and-save');



});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
});
Route::group(['middleware' => ['auth']], function() {

    Route::resource('roles','RoleController');
    
    Route::resource('users','UserController');
    
    });