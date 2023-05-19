@extends('layouts.master')
@section('title', 'Edit User')
@section('css')
    <!--Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Users</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Edit User</span>
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
                    <form action="{{route('users.update', $user->id)}}" method="post" data-parsley-validate="">
                        @method('PUT')
                        @csrf
                        <div class="row mg-b-20">
                            <div class="form-group col-6">
                                <label>User Name<span class="tx-danger">*</span></label>
                                <input class="form-control" name="name" type="text" value="{{$user->name}}" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Email<span class="tx-danger">*</span></label>
                                <input class="form-control" name="email" type="email" value="{{$user->email}}" required>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <div class="form-group col-6">
                                <label>Password<span class="tx-danger">*</span></label>
                                <input class="form-control" name="password" type="password">
                            </div>
                            <div class="form-group col-6">
                                <label>Confirm Password<span class="tx-danger">*</span></label>
                                <input class="form-control" name="confirm-password" type="password">
                            </div>
                        </div>
                        <div class="row row-sm mg-b-20">
                            <div class="col-lg-6">
                                <label class="form-label">User Status</label>
                                <select name="status" id="user_status" class="form-control select2-no-search">
                                    @if($user->status === '1')
                                        <option value="1" selected>Enabled</option>
                                        <option value="0">Not Enabled</option>
                                    @else
                                        <option value="1">Enabled</option>
                                        <option value="0" selected>Not Enabled</option>
                                    @endif
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
                                                @if(in_array($role, $currentRoles))
                                                    <label class="ckbox"><input checked name="roles[]" value="{{$role}}" type="checkbox"><span>{{$role}}</span></label>
                                                @else
                                                    <label class="ckbox"><input name="roles[]" value="{{$role}}" type="checkbox"><span>{{$role}}</span></label>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-main-primary pd-x-20" type="submit">Update</button>
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
