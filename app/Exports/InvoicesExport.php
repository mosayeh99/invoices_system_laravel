<?php

namespace App\Exports;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoicesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(public $invoices)
    {

    }

    public function collection()
    {
        return InvoiceResource::collection($this->invoices);
    }

    public function headings(): array
    {
        return [
            'invoice No',
            'invoice Date',
            'Due Date',
            'Department',
            'Product',
            'Collection Amount',
            'Commission Amount',
            'Discount',
            'TAX Rate',
            'Status',
            'Payment Date',
            'Note',
            'Created by',
            'Created at'
        ];
    }
}
