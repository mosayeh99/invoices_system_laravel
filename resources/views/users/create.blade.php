@extends('layouts.master')
@section('title', 'Add User')
@section('css')
    <!--Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Users</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Add User</span>
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
                    <form action="{{route('users.store')}}" method="post" data-parsley-validate="">
                        @csrf
                        <div class="row mg-b-20">
                            <div class="form-group col-6">
                                <label>User Name<span class="tx-danger">*</span></label>
                                <input class="form-control" name="name" type="text" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Email<span class="tx-danger">*</span></label>
                                <input class="form-control" name="email" type="email" required>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <div class="form-group col-6">
                                <label>Password<span class="tx-danger">*</span></label>
                                <input class="form-control" name="password" type="password" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Confirm Password<span class="tx-danger">*</span></label>
                                <input class="form-control" name="confirm-password" type="password" required>
                            </div>
                        </div>
                        <div class="row row-sm mg-b-20">
                            <div class="col-lg-6">
                                <label class="form-label">User Status</label>
                                <select name="status" class="form-control select2-no-search">
                                    <option value="1">Enabled</option>
                                    <option value="0">Not Enabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label class="form-label">User Role</label>
                                    <div class="row">
                                        @foreach($roles as $role)
                                        <div class="col-lg-1">
                                            <label class="ckbox"><input name="roles[]" value="{{$role}}" type="checkbox"><span>{{$role}}</span></label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-main-primary pd-x-20" type="submit">Add</button>
                            <a class="btn btn-secondary" href="{{ route('users.index') }}">Back</a>
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
    <!--Internal  Parsley.min js -->
    <script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
    <!-- Internal Form-validation js -->
    <script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
@endsection
