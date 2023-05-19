<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Department;
use App\Models\Invoice;
use App\Traits\ClientsSearchTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportClientsController extends Controller
{
    use ClientsSearchTrait;

    public function index(Request $request)
    {
        $invoices = Invoice::orderBy('id', 'desc')->get();
        $departments = Department::orderBy('id', 'desc')->get();
        return view('reports.clients_reports', compact('invoices', 'departments'));
    }

    public function getSearchResult(Request $request)
    {
        $result = $this->getInvoicesSearchResult($request);
        if ($result['invoices']) {
            return view('reports.clients_reports', [
                'invoices' => $result['invoices'],
                'departments' => Department::orderby('id', 'desc')->get(),
                // Search Values
                'department_id' => $request->department_id,
                'product_id' => $request->department_id,
                'date_from' => $result['date_from'],
                'date_to' => $result['date_to']
            ]);
        }
        return back()->with('error', 'Department Is Required');
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
