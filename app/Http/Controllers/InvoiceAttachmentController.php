<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceAttachmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Show Invoices')->only(['index','show','download']);
        $this->middleware('permission:Add Invoices')->only('store');
        $this->middleware('permission:Delete Invoices')->only('destroy');
    }

    public function store(Request $request)
    {
        $isInvoiceExist = Invoice::withTrashed()->where('id', $request->invoice_id)->exists();
        if ($isInvoiceExist) {
            $validated = $request->validate([
                'invoice_id' => 'required',
                "files.*" => 'mimes:pdf,jpeg,jpg,png',
            ]);
            $files = $request->file('files');
            foreach ($files as $file){
                $file_name = $file->getClientOriginalName();
                $file->storeAs("invoices_attachments/$request->invoice_number", $file_name, 'public');
                InvoiceAttachment::create([
                    'invoice_id' => $request->invoice_id,
                    'file_path' => $file_name,
                    'user_id' => auth()->user()->id
                ]);
            }
            return back()->with('status', 'Files Added Successfully');
        }
        return back()->with('error', 'Invoices Not Found');
    }

    public function download($invoice_number,$file_name)
    {
        if (Storage::disk('public')->exists("invoices_attachments/$invoice_number/$file_name")) {
            return Storage::download("public/invoices_attachments/$invoice_number/$file_name");
        }
        return back()->with('error', 'File Not Found');
    }

    public function show($invoice_number, $file_name)
    {
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        if ($extension === 'pdf') {
            $headers = ['Content-Type' => 'application/pdf'];
            return Storage::response("public/invoices_attachments/$invoice_number/$file_name", 'attachment', $headers);
        }
        return Storage::response("public/invoices_attachments/$invoice_number/$file_name");
    }

    public function destroy(Request $request)
    {
        if (Storage::disk('public')->exists("invoices_attachments/$request->invoice_number/$request->file_name")) {
            Storage::disk('public')->delete("invoices_attachments/$request->invoice_number/$request->file_name");
            InvoiceAttachment::where('file_path', $request->file_name)->delete();
            return back()->with('status', 'File Deleted Successfully');
        }
        return back()->with('error', 'File Not Found');
    }
}
