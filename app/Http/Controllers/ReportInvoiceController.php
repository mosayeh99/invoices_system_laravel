<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Traits\InvoicesSearchTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportInvoiceController extends Controller
{
    use InvoicesSearchTrait;

    public function index()
    {
        $invoices = Invoice::orderBy('id', 'desc')->get();
        return view('reports.invoices_reports', compact('invoices'));
    }

    public function getSearchResult(Request $request)
    {
        $result = $this->getInvoicesSearchResult($request);
        if ($result['invoices']) {
            return view('reports.invoices_reports', [
                'invoices' => $result['invoices'],
                'payment_status' => $result['payment_status'],
                'date_from' => $result['date_from'],
                'date_to' => $result['date_to']
            ]);
        }
        return back()->with('error', 'Enter "Payment Status" or "From Date" At Least');
    }

    public function exportSearchResult(Request $request)
    {
        $result = $this->getInvoicesSearchResult($request);
        if ($result['invoices']) {
            return Excel::download(new InvoicesExport($result['invoices']), 'invoices.xlsx');
        }
        return back()->with('error', 'Invoices Not Found');
    }
}
