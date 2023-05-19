<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Department;
use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use App\Models\Product;
use App\Models\User;
use App\Notifications\InvoiceCreated;
use App\Notifications\InvoicePaid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Show Invoices')->only(['index','show','getPaidInvoices','getPartialPaidInvoices','getUnPaidInvoices','getArchives']);
        $this->middleware('permission:Add Invoices')->only(['create','store']);
        $this->middleware('permission:Edit Invoices')->only(['edit','update']);
        $this->middleware('permission:Delete Invoices')->only('destroy');
        $this->middleware('permission:Archive Invoices')->only(['archive','restore']);
        $this->middleware('permission:Print Invoices')->only('print');
        $this->middleware('permission:Export Invoices Excel')->only(['exportAll', 'exportPaid', 'exportUnpaid', 'exportPartialPaid']);
    }

    public function index()
    {
        $invoices = Invoice::orderBy('id', 'desc')->get();
        return view('invoices.invoices', compact('invoices'));
    }

    public function create()
    {
        $departments = Department::orderBy('id', 'desc')->get();
        return view('invoices.add_invoices', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "invoice_number" => 'required|unique:invoices',
            "invoice_date" => 'required|date',
            "due_date" => 'required|date',
            "department_id" => [
                'required',
                Rule::in(Department::pluck('id'))
            ],
            "product_id" => [
                'required',
                Rule::in(Product::pluck('id'))
            ],
            "collection_amount" => 'required|numeric',
            "commission_amount" => 'required|numeric',
            "discount" => 'numeric',
            "tax_value" => 'required|numeric',
            "total" => 'required|numeric',
            "files.*" => 'mimes:pdf,jpeg,jpg,png'
        ]);

        $invoice = Invoice::create([
            "invoice_number" => $request->invoice_number,
            "invoice_date" => $request->invoice_date ? Carbon::parse($request->invoice_date)->format('Y-m-d') : null,
            "due_date" => $request->due_date ? Carbon::parse($request->due_date)->format('Y-m-d') : null,
            "department_id" => $request->department_id,
            "product_id" => $request->product_id,
            "collection_amount" => $request->collection_amount,
            "commission_amount" => $request->commission_amount,
            "discount" => $request->discount,
            "tax_rate" => $request->tax_rate,
            "tax_value" => $request->tax_value,
            "total" => $request->total,
            "note" => $request->note,
            "user_id" => auth()->user()->id
        ]);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file){
                $file_name = $file->getClientOriginalName();
                $file->storeAs("invoices_attachments/$request->invoice_number", $file_name, 'public');
                InvoiceAttachment::create([
                    'invoice_id' => $invoice->id,
                    'file_path' => $file_name,
                    'user_id' => auth()->user()->id
                ]);
            }
        }
        $user = User::findOrFail(1);
//        $user->notify(new InvoiceCreated($invoice));
        $invoices = Invoice::orderBy('id', 'desc')->get();
        return view('invoices.invoices')->with(['invoices' => $invoices, 'status' => 'Invoice Created Successfully']);
    }

    public function show($id)
    {
        $isInvoiceExist = Invoice::withTrashed()->where('id', $id)->exists();
        if ($isInvoiceExist){
            $invoice = Invoice::withTrashed()->findOrFail($id);
            $attachments = InvoiceAttachment::where('invoice_id', $id)->get();
            return view('invoices.invoices_details', compact('invoice', 'attachments'));
        }
        return back()->with('error', 'Invoice Not Found');
    }

    public function edit($id)
    {
        $isInvoiceExist = Invoice::withTrashed()->where('id', $id)->exists();
        if ($isInvoiceExist) {
            $invoice = Invoice::withTrashed()->findOrFail($id);
            $departments = Department::all();
            return view('invoices.edit_invoices', compact('invoice','departments'));
        }
        return back()->with('error', 'Invoice Not Found');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "invoice_number" => [
                'required',
                Rule::unique('invoices')->ignore($id)
            ],
            "invoice_date" => 'required|date',
            "due_date" => 'required|date',
            "department_id" => [
                'required',
                Rule::in(Department::pluck('id'))
            ],
            "product_id" => [
                'required',
                Rule::in(Product::pluck('id'))
            ],
            "collection_amount" => 'required|numeric',
            "commission_amount" => 'required|numeric',
            "discount" => 'numeric',
            "tax_value" => 'required|numeric',
            "total" => 'required|numeric',
        ]);

        $isInvoiceExist = Invoice::withTrashed()->where('id', $id)->exists();
        if ($isInvoiceExist) {
            $invoice = Invoice::withTrashed()->find($id);
            $invoiceStatus = $invoice->status;
            $isUpdated = $invoice->update([
                "invoice_number" => $request->invoice_number,
                "invoice_date" => $request->invoice_date ? Carbon::parse($request->invoice_date)->format('Y-m-d') : null,
                "due_date" => $request->due_date ? Carbon::parse($request->due_date)->format('Y-m-d') : null,
                "department_id" => $request->department_id,
                "product_id" => $request->product_id,
                "collection_amount" => $request->collection_amount,
                "commission_amount" => $request->commission_amount,
                "discount" => $request->discount,
                "tax_rate" => $request->tax_rate,
                "tax_value" => $request->tax_value,
                "total" => $request->total,
                "status" => $request->status,
                "payment_date" => $request->payment_date ? Carbon::parse($request->payment_date)->format('Y-m-d') : null,
                "note" => $request->note,
                "user_id" => auth()->user()->id
            ]);

            if ($isUpdated  && $invoiceStatus !== 'Paid' && $invoice->status === 'Paid'){
                $users = User::role('Admin')->get();
                Notification::send($users, new InvoicePaid($invoice));
            }

            return back()->with('status', 'Invoice Updated Successfully');
        }
        return back()->with('error', 'Invoice Not Found');
    }

    public function destroy(Request $request)
    {
        $isInvoiceExist = Invoice::withTrashed()->where('id', $request->invoice_id)->exists();
        if ($isInvoiceExist){
            $invoice = Invoice::withTrashed()->findOrFail($request->invoice_id);
            $invoice->forceDelete();
            Storage::disk('public')->deleteDirectory("invoices_attachments/$invoice->invoice_number");
            return back()->with('status', 'Invoice Deleted Successfully');
        }
        return back()->with('error', 'Invoice Not Found');
    }

    public function print($id)
    {
        $isInvoiceExist = Invoice::withTrashed()->where('id', $id)->exists();
        if ($isInvoiceExist){
            $invoice = Invoice::withTrashed()->findOrFail($id);
            return view('invoices.print_invoices', compact('invoice'));
        }
        return back()->with('error', 'Invoice Not Found');
    }

    // -----------Get Invoices Based On Status------------
    public function getPaidInvoices()
    {
        $invoices = Invoice::where('status', 'Paid')->orderBy('id', 'desc')->get();
        return view('invoices.paid_invoices', compact('invoices'));
    }

    public function getPartialPaidInvoices()
    {
        $invoices = Invoice::where('status', 'Partially Paid')->orderBy('id', 'desc')->get();
        return view('invoices.partial-paid_invoices', compact('invoices'));
    }

    public function getUnPaidInvoices()
    {
        $invoices = Invoice::where('status', 'Not Paid')->orderBy('id', 'desc')->get();
        return view('invoices.unpaid_invoices', compact('invoices'));
    }

    // -----------Archived Invoices Methods-------------
    public function getArchives()
    {
        $invoices = Invoice::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('invoices.archives_invoices', compact('invoices'));
    }

    public function archive($id)
    {
        $isInvoiceExist = Invoice::where('id', $id)->exists();
        if ($isInvoiceExist){
            Invoice::where('id', $id)->delete();
            return back()->with('status', 'Invoice Archived Successfully');
        }
        return back()->with('error', 'Invoice Not Found');
    }

    public function restore($id)
    {
        $isInvoiceExist = Invoice::onlyTrashed()->where('id', $id)->exists();
        if ($isInvoiceExist){
            Invoice::onlyTrashed()->where('id', $id)->restore();
            return back()->with('status', 'Invoice Restored Successfully');
        }
        return back()->with('error', 'Invoice Not Found');
    }

    // --------------- Export Invoices as Excel ----------------
    public function export($status)
    {
        if ($status === 'unpaid') {
            $invoices = Invoice::where('status', 'Not Paid')->orderBy('id', 'desc')->get();
        }elseif ($status === 'paid'){
            $invoices = Invoice::where('status', 'Paid')->orderBy('id', 'desc')->get();
        }elseif ($status === 'partially-paid') {
            $invoices = Invoice::where('status', 'Partially Paid')->orderBy('id', 'desc')->get();
        }elseif ($status === 'archives'){
            $invoices = Invoice::onlyTrashed()->orderBy('id', 'desc')->get();
        }else{
            $invoices = Invoice::orderBy('id', 'desc')->get();
        }
        return Excel::download(new InvoicesExport($invoices), 'invoices.xlsx');
    }
}
