@extends('layouts.master')
@section('title', 'Vince')

@section('content')
    <!-- row -->
    <div class="row row-sm mt-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">TOTAL INVOICES</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{$invoices_total}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Invoices Num: {{$invoices_count}}</p>
                            </div>
                            <span class="float-right my-auto ml-auto">
											<i class="icon ion-ios-stats text-white"></i>
											<span class="text-white op-7"> 100%</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-success-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">PAID INVOICES</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{$paid_invoices_sum}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Invoices Num: {{$paid_invoices_count}}</p>
                            </div>
                            <span class="float-right my-auto ml-auto">
											<i class="icon ion-ios-stats text-white"></i>
											<span class="text-white op-7"> {{$paid_invoices_rate}}%</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">PARTIALLY PAID INVOICES</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{$partialPaid_invoices_sum}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Invoices Num: {{$partialPaid_invoices_count}}</p>
                            </div>
                            <span class="float-right my-auto ml-auto">
											<i class="icon ion-ios-stats text-white"></i>
											<span class="text-white op-7"> {{$partialPaid_invoices_rate}}%</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">UNPAID INVOICES</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">${{$unpaid_invoices_sum}}</h4>
                                <p class="mb-0 tx-12 text-white op-7">Invoices Num: {{$unpaid_invoices_count}}</p>
                            </div>
                            <span class="float-right my-auto ml-auto">
											<i class="icon ion-ios-stats text-white"></i>
											<span class="text-white op-7"> {{$unpaid_invoices_rate}}%</span>
										</span>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <h4 class="card-title mb-0">Invoices status</h4>
                </div><hr class="mb-0">
                <div class="card-body">
                    <div style="width:100%;">
                        {!! $invoicesChart->render() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-5">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <h4 class="card-title mb-0">Departments status</h4>
                </div><hr class="mb-0">
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Number of Departments: <span class="tx-light">{{$departments_count}}</span></h6>
                    </div>
                    <div style="width:100%;">
                        {!! $departmentsChart->render() !!}
                    </div>
                </div>
            </div>
        </div>
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
    <!--Internal  index js -->
    <script src="{{URL::asset('assets/js/index.js')}}"></script>
@endsection
