@extends('layouts.master')
@section('title', 'Invoices')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">Invoices</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Invoices List</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- row -->
                <div class="row row-sm">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header pb-0 d-flex justify-content-between">
                                @can('Add Invoices')
                                    <div>
                                        <a href="{{route('invoices.create')}}" class="btn btn-primary">Add Invoices</a>
                                    </div>
                                @endcan
                                @can('Export Invoices Excel')
                                    <div>
                                        <a href="{{route('invoices.export', 'all')}}" class="btn btn-success-gradient">Export Excel</a>
                                    </div>
                                @endcan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table text-md-nowrap" id="example1">
                                        <thead>
                                        <tr>
                                            <th class="wd-5p-f border-bottom-0">#</th>
                                            <th class="wd-10p-f border-bottom-0">Invoices Num</th>
                                            <th class="wd-10p-f border-bottom-0">Department</th>
                                            <th class="wd-10p-f border-bottom-0">Product</th>
                                            <th class="wd-15p-f border-bottom-0">Collection Amount</th>
                                            <th class="wd-15p-f border-bottom-0">Commission Amount</th>
                                            <th class="wd-5p-f border-bottom-0">Status</th>
                                            <th class="wd-20p-f wd-lg-10p-f border-bottom-0">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $i=0 @endphp
                                        @foreach($invoices as $invoice)
                                            <tr>
                                                <td>{{++$i}}</td>
                                                <td>
                                                    <a href="{{route('invoices.show', $invoice->id)}}">{{$invoice->invoice_number}}</a>
                                                </td>
                                                <td>{{$invoice->department->name}}</td>
                                                <td>{{$invoice->product->name}}</td>
                                                <td>{{$invoice->collection_amount}}</td>
                                                <td>{{$invoice->commission_amount}}</td>
                                                <td>
                                                    @if($invoice->status === 'Paid')
                                                        <span class="badge badge-success">{{$invoice->status}}</span>
                                                    @elseif($invoice->status === 'Not Paid')
                                                        <span class="badge badge-danger">{{$invoice->status}}</span>
                                                    @else
                                                        <span class="badge badge-warning">{{$invoice->status}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        @canany(['Edit Invoices','Print Invoices','Archive Invoices','Delete Invoices'])
                                                            <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary px-2 py-0" data-toggle="dropdown" id="dropdownMenuButton" type="button">Actions <i class="fas fa-caret-down ml-1"></i></button>
                                                        @endcan
                                                        <div  class="dropdown-menu tx-13">
                                                            @can('Edit Invoices')
                                                                <a class="dropdown-item" href="{{route('invoices.edit', $invoice->id)}}">Edit</a>
                                                            @endcan
                                                            @can('Print Invoices')
                                                                <a class="dropdown-item" href="{{route('invoices.print', $invoice->id)}}">Print</a>
                                                            @endcan
                                                            @can('Archive Invoices')
                                                                <form action="{{route('invoices.archive', $invoice->id)}}" method="post">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item" style="outline: none">Archive</button>
                                                                </form>
                                                            @endcan
                                                            @can('Delete Invoices')
                                                                <button class="dropdown-item" data-toggle="modal" data-target="#deleteInvoiceModal" onclick="document.querySelector('#delete_invoice_id').value = {{$invoice->id}}">Delete</button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
        <!-- Model | Delete Invoice -->
        <div class="modal fade" id="deleteInvoiceModal">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Delete Product</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <form action="invoices/destroy" method="post">
                                @method('DELETE')
                                @csrf
                                <div class="modal-body">
                                    <p>Are you sure want to delete this invoice?</p>
                                    <div id="view_file_to_delete" class="wd-100 m-auto"></div>
                                    <input type="hidden" id="delete_invoice_id" name="invoice_id">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Confirm</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        @if (session('status'))
            <script>
                window.onload = () => {
                    notif({
                        msg: "{{session('status')}}",
                        type: "success"
                    });
                }
            </script>
        @endif
        @if (session('error'))
            <script>
                window.onload = () => {
                    notif({
                        msg: "{{ session('error') }}",
                        type: "error"
                    });
                }
            </script>
        @endif
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal  Datatable js -->
    <script src="{{asset('assets/js/table-data.js')}}"></script>
    <!--Internal  Notify js -->
    <script src="{{asset('assets/plugins/notify/js/notifIt.js')}}"></script>
@endsection
