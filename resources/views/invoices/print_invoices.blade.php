@extends('layouts.master')
@section('title', 'Print Invoices')
@section('css')
    <style>
        @media print {
            #print_invoice_btn {
                display: none;
            }
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoices</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Print</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div id="invoice_area_print" class="main-content-body-invoice">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="row mg-t-20">
                            <div class="col-12 col-md-6">
                                <label class="tx-gray-600">Invoice Information</label>
                                <p class="invoice-info-row"><span>Invoice No</span> <span>{{$invoice->invoice_number}}</span></p>
                                <p class="invoice-info-row"><span>Status</span>
                                    @if($invoice->status === 'Paid')
                                        <span class="badge badge-success">{{$invoice->status}}</span>
                                    @elseif($invoice->status === 'Not Paid')
                                        <span class="badge badge-danger">{{$invoice->status}}</span>
                                    @else
                                        <span class="badge badge-warning">{{$invoice->status}}</span>
                                    @endif
                                </p>
                                <p class="invoice-info-row"><span>Invoice Date:</span> <span>{{\Carbon\Carbon::parse($invoice->invoice_date)->format('Y-M-d')}}</span></p>
                                <p class="invoice-info-row"><span>Due Date:</span> <span>{{\Carbon\Carbon::parse($invoice->due_date)->format('Y-M-d')}}</span></p>
                                <p class="invoice-info-row"><span>Payment Date:</span> <span>{{\Carbon\Carbon::parse($invoice->payment_date)->format('Y-M-d')}}</span></p>
                                <p class="invoice-info-row"><span>Invoice Creator</span> <span>{{$invoice->user->name}}</span></p>
                            </div>
                            <div class="col-6 d-none d-md-block">
                                <h1 class="invoice-title tx-right">Invoice</h1>
                            </div>
                        </div>
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th class="wd-15p tx-center">Product</th>
                                    <th class="wd-15p tx-center">Department</th>
                                    <th class="wd-15p tx-center">Collection Amount</th>
                                    <th class="wd-15p tx-center">Commission Amount</th>
                                    <th class="wd-15p tx-center">Discount</th>
                                    <th class="wd-15p tx-center">TAX Rate</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="tx-center">{{$invoice->product->name}}</td>
                                    <td class="tx-center">{{$invoice->department->name}}</td>
                                    <td class="tx-center">{{$invoice->collection_amount}}</td>
                                    <td class="tx-center">{{$invoice->commission_amount}}</td>
                                    <td class="tx-center">{{$invoice->discount}}</td>
                                    <td class="tx-center">{{$invoice->tax_rate ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td class="valign-middle" colspan="3" rowspan="4">
                                        <div class="invoice-notes">
                                            <label class="main-content-label tx-13">Notes</label>
                                            <p>{{$invoice->note}}</p>
                                        </div><!-- invoice-notes -->
                                    </td>
                                    <td class="tx-left">Sub-Total</td>
                                    <td class="tx-right" colspan="2">{{$invoice->commission_amount - $invoice->discount}}$</td>
                                </tr>
                                <tr>
                                    <td class="tx-left">Tax Value</td>
                                    <td class="tx-right" colspan="2">{{$invoice->tax_value}}$</td>
                                </tr>
                                <tr>
                                    <td class="tx-left tx-uppercase tx-bold tx-inverse">Total</td>
                                    <td class="tx-right" colspan="2">
                                        <h4 class="tx-primary tx-bold">{{$invoice->total}}$</h4>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr class="mg-b-40">
                        <button id="print_invoice_btn" onclick="printInvoice()" class="btn btn-danger float-left mt-2 mr-2">
                            <i class="mdi mdi-printer ml-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
    <!-- Print Invoice -->
    <script>
        function printInvoice() {
            let printContents = document.getElementById('invoice_area_print').innerHTML;
            let originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endsection
