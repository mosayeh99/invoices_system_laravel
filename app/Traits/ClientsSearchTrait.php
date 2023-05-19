<?php

namespace App\Traits;

use App\Models\Department;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait ClientsSearchTrait
{
    public $invoices;
    public function getInvoicesSearchResult(Request $request)
    {
        $department_id = $request->department_id;
        $product_id = $request->product_id;
        $date_from = $request->date_from ? Carbon::parse($request->date_from)->format('Y-m-d') : null;
        $date_to = $request->date_to ? Carbon::parse($request->date_to)->format('Y-m-d') : null;

        if ($department_id && $product_id && !$date_from && !$date_to) {
            $this->invoices = Invoice::where('product_id', $product_id)
                ->where('department_id', $department_id)
                ->orderBy('id', 'desc')->get();
        }

        if ($department_id && $product_id && $date_from && !$date_to) {
            $this->invoices = Invoice::where('product_id', $product_id)
                ->where('department_id', $department_id)
                ->where('created_at', '>=', $date_from)
                ->orderBy('id', 'desc')->get();
        }

        if ($department_id && $product_id && $date_from && $date_to) {
            $this->invoices = Invoice::where('product_id', $product_id)
                ->where('department_id', $department_id)
                ->whereBetween('created_at', [$date_from,$date_to])
                ->orderBy('id', 'desc')->get();
        }

        return [
            'invoices' => $this->invoices,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];
    }
}
