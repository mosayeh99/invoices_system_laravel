<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice_number' => $this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'department' => $this->department->name,
            'product' => $this->product->name,
            'collection_amount' => $this->collection_amount,
            'commission_amount' => $this->commission_amount,
            'discount' => $this->discount,
            'tax_rate' => $this->tax_rate,
            'status' => $this->status,
            'payment_date' => $this->payment_date,
            'note' => $this->note,
            'created_by' => $this->user->name,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
