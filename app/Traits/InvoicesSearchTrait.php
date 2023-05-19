<?php

namespace App\Traits;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait InvoicesSearchTrait
{
    public $invoices;
    public function getInvoicesSearchResult(Request $request)
    {
        $payment_status = $request->payment_status;
        $date_from = $request->date_from ? Carbon::parse($request->date_from)->format('Y-m-d') : null;
        $date_to = $request->date_to ? Carbon::parse($request->date_to)->format('Y-m-d') : null;

        if ($payment_status && !$date_from && !$date_to) {
            $this->invoices = Invoice::where('status', $payment_status)->orderBy('id', 'desc')->get();
        }

        if ($payment_status && $date_from && !$date_to) {
            $this->invoices = Invoice::where('status', $payment_status)
                ->where('created_at', '>=', $date_from)->orderBy('id', 'desc')->get();
        }

        if (!$payment_status && $date_from && !$date_to) {
            $this->invoices = Invoice::where('created_at', '>=', $date_from)->orderBy('id', 'desc')->get();
        }

        if ($payment_status && $date_from && $date_to) {
            $this->invoices = Invoice::where('status', $payment_status)
                ->whereBetween('created_at', [$date_from,$date_to])->orderBy('id', 'desc')->get();
        }

        return [
            'invoices' => $this->invoices,
            'payment_status' => $payment_status,
            'date_from' => $date_from,
            'date_to' => $date_to
        ];
    }
}
