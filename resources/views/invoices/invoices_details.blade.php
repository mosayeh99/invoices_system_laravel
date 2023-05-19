@extends('layouts.master')
@section('title', 'Invoice Details')
@section('css')
    <!---Internal Fileupload css-->
    <link href="{{URL::asset('assets/plugins/fileuploads/css/fileupload.css')}}" rel="stylesheet" type="text/css"/>
    <!--Internal   Notify -->
    <link href="{{asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoices</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Invoices Details</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            @can('Edit Invoices')
                <div class="pr-1 mb-3 mb-xl-0">
                    <a href="{{route('invoices.edit', $invoice->id)}}" class="btn btn-primary">Edit</a>
                </div>
            @endcan
            @can('Print Invoices')
                <div class="pr-1 mb-3 mb-xl-0">
                    <a href="{{route('invoices.print', $invoice->id)}}" class="btn btn-info">Print</a>
                 </div>
            @endcan
            @can('Archive Invoices')
                <div class="pr-1 mb-3 mb-xl-0">
                @if($invoice->deleted_at)
                    <a class="btn btn-secondary" href="{{route('invoices.restore', $invoice->id)}}">Restore</a>
                @else
                    <form action="{{route('invoices.archive', $invoice->id)}}" method="post">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-secondary">Archive</button>
                    </form>
                @endif
            </div>
            @endcan
            @can('Delete Invoices')
                <div class="pr-1 mb-3 mb-xl-0">
                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteInvoiceModal" onclick="document.querySelector('#delete_invoice_id').value = {{$invoice->id}}">Delete</button>
            </div>
            @endcan
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="panel panel-primary tabs-style-2">
                            <div class=" tab-menu-heading">
                                <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs main-nav-line">
                                        <li><a href="#tab4" class="nav-link active" data-toggle="tab">Invoice Details</a></li>
                                        <li><a href="#tab5" class="nav-link" data-toggle="tab">Payment Status</a></li>
                                        <li><a href="#tab6" class="nav-link" data-toggle="tab">Attachments</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body main-content-body-right border">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab4">
                                        <div class="table-responsive">
                                            <table class="table table-striped mg-b-0 text-md-nowrap">
                                                <tbody>
                                                <tr>
                                                    <th>Invoice Num</th>
                                                    <td>{{$invoice->invoice_number}}</td>
                                                    <th>Invoice Date</th>
                                                    <td>{{$invoice->invoice_date}}</td>
                                                    <th>Invoice Due Date</th>
                                                    <td>{{$invoice->due_date}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Department</th>
                                                    <td>{{$invoice->department->name}}</td>
                                                    <th>Product</th>
                                                    <td>{{$invoice->product->name}}</td>
                                                    <th>Collection Amount</th>
                                                    <td>{{$invoice->collection_amount}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Commission Amount</th>
                                                    <td>{{$invoice->commission_amount}}</td>
                                                    <th>Discount</th>
                                                    <td>{{$invoice->discount}}</td>
                                                    <th>TAX Rate</th>
                                                    <td>{{$invoice->tax_rate}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Value Added TAX</th>
                                                    <td>{{$invoice->tax_value}}</td>
                                                    <th>Total Including TAX</th>
                                                    <td>{{$invoice->total}}</td>
                                                    <th>Status</th>
                                                    <td>
                                                        @if($invoice->status === 'Paid')
                                                            <span class="badge badge-success">{{$invoice->status}}</span>
                                                        @elseif($invoice->status === 'Not Paid')
                                                            <span class="badge badge-danger">{{$invoice->status}}</span>
                                                        @else
                                                            <span class="badge badge-warning">{{$invoice->status}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Created By</th>
                                                    <td>{{$invoice->user->email}}</td>
                                                    <th>Created At</th>
                                                    <td>{{$invoice->created_at}}</td>
                                                    <th>Notes</th>
                                                    <td>{{$invoice->note}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- bd -->
                                    </div>
                                    <div class="tab-pane" id="tab5">
                                        <div class="table-responsive">
                                            <table class="table table-striped mg-b-0 text-md-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Invoices Num</th>
                                                        <th>Product</th>
                                                        <th>Department</th>
                                                        <th>Status</th>
                                                        <th>Payment Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>{{$invoice->invoice_number}}</th>
                                                        <td>{{$invoice->product->name}}</td>
                                                        <td>{{$invoice->department->name}}</td>
                                                        <td>
                                                            @if($invoice->status === 'Paid')
                                                                <span class="badge badge-success">{{$invoice->status}}</span>
                                                            @elseif($invoice->status === 'Not Paid')
                                                                <span class="badge badge-danger">{{$invoice->status}}</span>
                                                            @else
                                                                <span class="badge badge-warning">{{$invoice->status}}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$invoice->payment_date}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- bd -->
                                    </div>
                                    <div class="tab-pane" id="tab6">
                                        @can('Add Invoices')
                                            <form action="{{route('invoiceAttachments.store')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div>
                                                    <p class="text-danger mb-2">Accepted formats: pdf, jpeg, jpg, png </p>
                                                    <h6 class="card-title mb-2">Upload New File</h6>
                                                </div>
                                                <div class="mb-2">
                                                    <input id="demo" type="file" class="dropify" name="files[]" data-height="100" accept=".pdf, .jpg, .png, image/jpeg, image/png" multiple>
                                                    <input type="hidden" name="invoice_id" value="{{$invoice->id}}">
                                                    <input type="hidden" name="invoice_number" value="{{$invoice->invoice_number}}">
                                                </div>
                                                <div class="d-flex justify-content-end mb-5">
                                                    <button type="submit" class="btn btn-primary" href="">Upload</button>
                                                </div>
                                            </form>
                                        @endcan
                                        <div class="table-responsive">
                                            <table class="table table-striped mg-b-0 text-md-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Attachment</th>
                                                        <th>Add By</th>
                                                        <th>Add At</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php $i=0; @endphp
                                                @foreach($attachments as $file)
                                                    <tr>
                                                        <th scope="row">{{++$i}}</th>
                                                        <td>
                                                            @if(in_array((pathinfo($file->file_path, PATHINFO_EXTENSION)), ['jpg', 'png', 'image/jpeg', 'image/png']))
                                                                <div class="wd-40">
                                                                    <img src="{{asset("storage/invoices_attachments/$invoice->invoice_number/$file->file_path")}}">
                                                                </div>
                                                            @elseif(pathinfo($file->file_path, PATHINFO_EXTENSION) === 'pdf')
                                                                <span class="badge badge-secondary">{{$file->file_path}}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$file->user->email}}</td>
                                                        <td>{{$file->created_at}}</td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="{{route('invoiceAttachments.show', [$invoice->invoice_number, $file->file_path])}}" class="btn btn-primary h-100 px-2 py-1" target="_blank">View</a>
                                                                <a href="{{route('invoiceAttachments.download', [$invoice->invoice_number, $file->file_path])}}" class="btn btn-info h-100 px-2 py-1">Download</a>
                                                                @can('Delete Invoices')
                                                                    <button class="btn btn-danger h-100 px-2 py-1" data-toggle="modal" data-target="#deleteFileModal" onclick="setDeletedFileId(this)" data-invoice-number="{{$invoice->invoice_number}}" data-file-name="{{$file->file_path}}">Delete</button>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div><!-- bd -->
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <!-- Model | Delete Attachment -->
    <div class="modal fade" id="deleteFileModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Delete Product</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{route('invoiceAttachments.destroy')}}" method="post">
                    @method('DELETE')
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure want to delete this file?</p>
                        <div id="view_file_to_delete" class="wd-100 m-auto"></div>
                        <input type="hidden" id="delete_file_name" name="file_name">
                        <input type="hidden" id="delete_file_invoice_number" name="invoice_number">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
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
            </div>
            </form>
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
    <!--Internal Fileuploads js-->
    <script src="{{URL::asset('assets/plugins/fileuploads/js/fileupload.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/fileuploads/js/file-upload.js')}}"></script>
    <!--Internal  Notify js -->
    <script src="{{asset('assets/plugins/notify/js/notifIt.js')}}"></script>
    <script>
        function setDeletedFileId(e){
            let fileName = e.dataset.fileName;
            let invoiceNum = e.dataset.invoiceNumber;
            document.querySelector('#delete_file_name').value = fileName;
            document.querySelector('#delete_file_invoice_number').value = invoiceNum;
            let viewFileToDelete = document.querySelector('#view_file_to_delete');
            let fileExtension = fileName.split(".").pop();
            let content = '';
            if(fileExtension === 'pdf') {
                content = `<span class="badge badge-secondary d-flex justify-content-center p-2">${fileName}</span>`
            }else {
                let imgSrc = "{{asset('storage/invoices_attachments/:filepath')}}"
                imgSrc = imgSrc.replace(':filepath', `${invoiceNum}/${fileName}`);
                content = `<img src=${imgSrc}>`
            }
            viewFileToDelete.innerHTML = content;
        }
    </script>
@endsection
