@extends('layouts.master')
@section('title', 'Add Invoice')
@section('css')
    <!--Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <!--Internal Fileupload css-->
    <link href="{{ asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
    <!--Internal Fancy uploader css-->
    <link href="{{ asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Invoices</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Add Invoice</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- 1 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label for="inputName" class="control-label">Invoices Num<span class="tx-danger">*</span></label>
                                <input type="text" class="form-control" id="inputName" name="invoice_number" value="{{old('invoice_number')}}">
                            </div>
                            <div class="col mb-3">
                                <label>Invoices Date<span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" name="invoice_date" placeholder="YYYY-MM-DD" type="text" value="{{old('invoice_date') ?? date('Y-m-d') }}">
                            </div>
                            <div class="col mb-3">
                                <label>Invoices Due Date<span class="tx-danger">*</span></label>
                                <input class="form-control fc-datepicker" name="due_date" placeholder="YYYY-MM-DD" type="text"  value="{{old('due_date')}}">
                            </div>
                        </div>
                        {{-- 2 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label class="control-label">Department<span class="tx-danger">*</span></label>
                                <select name="department_id" onchange="getProductsOfDepartment(this.value)" class="form-control select2">
                                    <option selected disabled></option>
                                    @foreach ($departments as $department)
                                        <option value="{{$department->id}}" {{old('department_id') == $department->id ? 'selected' : ''}}> {{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Product<span class="tx-danger">*</span></label>
                                <select id="products" name="product_id" class="form-control select2">
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Collection Amount<span class="tx-danger">*</span></label>
                                <input type="text" class="form-control" value="{{old('collection_amount')}}" name="collection_amount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                        </div>
                        {{-- 3 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label class="control-label">Commission Amount<span class="tx-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="commission_amount" name="commission_amount"
                                       value="{{old('commission_amount')}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                       commissionCalculations()">
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Discount</label>
                                <input type="text" class="form-control form-control-lg" id="discount" name="discount"
                                       value="{{old('discount') ?? 0}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
                                       commissionCalculations()">
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Added TAX Rate</label>
                                <select onchange="commissionCalculations()" name="tax_rate" id="added_tax_rate" class="form-control select2-no-search">
                                    <option label="Choose One"></option>
                                    <option value="5%"  {{old('department_id') === '5%' ? 'selected' : ''}}>5%</option>
                                    <option value="10%"  {{old('department_id') === '10%' ? 'selected' : ''}}>10%</option>
                                </select>
                            </div>
                        </div>
                        {{-- 4 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label class="control-label">Value Added TAX</label>
                                <input type="text" name="tax_value" value="{{old('tax_value') ?? 0}}" class="form-control" id="value_added_tax" readonly>
                            </div>
                            <div class="col mb-3">
                                <label class="control-label">Total Including TAX</label>
                                <input type="text" name="total" value="{{old('total') ?? 0}}" class="form-control" id="total_including_tax" readonly>
                            </div>
                        </div>
                        {{-- 5 --}}
                        <div class="row">
                            <div class="col mb-3">
                                <label>Notes</label>
                                <textarea class="form-control" name="note" rows="3">{{old('note')}}</textarea>
                            </div>
                        </div><br>
                        <p class="text-danger">Accepted formats: pdf, jpeg, jpg, png </p>
                        <h5 class="card-title">Attachments</h5>
                        <div class="col-sm-12 col-md-12">
                            <input type="file" name="files[]" class="dropify" accept=".pdf,.jpg, .png, image/jpeg, image/png" multiple data-height="70"/>
                        </div><br>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Save Invoice</button>
                        </div>
                    </form>
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
    <!-- Internal Select2 js-->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal Fileuploads js-->
    <script src="{{ asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
    <!--Internal  Form-elements-->
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <script>
        function getProductsOfDepartment(departmentId){
            let url = "{{route('products.show', ':id')}}";
            url = url.replace(':id', departmentId);
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    let products = '';
                    data.forEach(p => products += `<option value="${p.id}">${p.name}</option>`)
                    document.querySelector('#products').innerHTML = products;
                })
        }
        function commissionCalculations(){
            let commissionAmount = document.querySelector('#commission_amount').value;
            let discount = document.querySelector('#discount').value;
            let addedTaxRate = parseInt(document.querySelector('#added_tax_rate').value) || 0;
            let valueAddedTax = (commissionAmount - discount) * (addedTaxRate / 100);
            document.querySelector('#value_added_tax').value = valueAddedTax.toFixed(2);
            document.querySelector('#total_including_tax').value = (commissionAmount - discount - valueAddedTax).toFixed(2);
        }
    </script>
@endsection
