<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportClientsController;
use App\Http\Controllers\ReportInvoiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function (){

    Route::fallback(function () {
        return view('404');
    });

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('notifications', function (){
        auth()->user()->unreadNotifications->markAsRead();
    })->name('notifications.markAsRead');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::resource('invoices',InvoiceController::class);
    Route::controller(InvoiceController::class)->group(function (){
        Route::get('print-invoices/{id}', 'print')->name('invoices.print');
        Route::get('export/invoices/{status}', 'export')->name('invoices.export');
        Route::get('paid-invoices', 'getPaidInvoices')->name('invoices.paid');
        Route::get('unpaid-invoices', 'getUnPaidInvoices')->name('invoices.unpaid');
        Route::get('partial-paid-invoices', 'getPartialPaidInvoices')->name('invoices.partiallyPaid');
        Route::get('archived-invoices', 'getArchives')->name('invoices.getArchives');
        Route::DELETE('archived-invoice/{id}', 'archive')->name('invoices.archive');
        Route::get('archived-invoice/restore/{id}', 'restore')->name('invoices.restore');
    });

    Route::resource('products',ProductController::class);
    Route::resource('departments',DepartmentController::class);

    Route::controller(InvoiceAttachmentController::class)->group(function (){
        Route::post('invoice_attachments', 'store')->name('invoiceAttachments.store');
        Route::get('invoice_attachments/{invoice_number}/{file_name}', 'show')->name('invoiceAttachments.show');
        Route::get('invoice_attachments/download/{invoice_number}/{file_name}', 'download')->name('invoiceAttachments.download');
        Route::DELETE('invoice_attachments/delete', 'destroy')->name('invoiceAttachments.destroy');
    });

    Route::controller(ReportInvoiceController::class)->group(function (){
        Route::get('reports/invoices','index')->name('reports.invoices.index');
        Route::post('reports/invoices','getSearchResult')->name('reports.invoices.search');
        Route::post('reports/invoices/export','exportSearchResult')->name('reports.invoices.export');
    });

    Route::controller(ReportClientsController::class)->group(function (){
        Route::get('reports/clients','index')->name('reports.clients.index');
        Route::post('reports/clients','getSearchResult')->name('reports.clients.search');
        Route::post('reports/clients/export','exportSearchResult')->name('reports.clients.export');
    });
});
