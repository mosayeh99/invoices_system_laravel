@extends('layouts.master')
@section('title', 'Add Role')
@section('css')
    <!--Internal  Font Awesome -->
    <link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <!--Internal  treeview -->
    <link href="{{URL::asset('assets/plugins/treeview/treeview.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Roles</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Add Role</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-20">
                <div class="card-body">
                    <form action="{{route('roles.store')}}" method="post">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="tx-bold">Role Name</label>
                            <input class="form-control" name="name" type="text">
                        </div>
                        <div class="main-content-label mg-b-5">
                            Select Permissions
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-lg-3">
                                <ul id="treeview1">
                                    <li><a href="#">Invoices</a>
                                        <ul>
                                            @foreach($permissions as $permission)
                                                @if(str_contains($permission->name, 'Invoices'))
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                                                        {{$permission->name}}
                                                    </label>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-3 mt-4 mt-lg-0">
                                <ul id="treeview2">
                                    <li><a href="#">Departments</a>
                                        <ul>
                                            @foreach($permissions as $permission)
                                                @if(str_contains($permission->name, 'Departments'))
                                                    <li>
                                                        <label>
                                                            <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                                                            {{$permission->name}}
                                                        </label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li><a href="#">Products</a>
                                        <ul>
                                            @foreach($permissions as $permission)
                                                @if(str_contains($permission->name, 'Products'))
                                                    <li>
                                                        <label>
                                                            <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                                                            {{$permission->name}}
                                                        </label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-3 mt-4 mt-lg-0">
                                <ul id="treeview3">
                                    <li><a href="#">Users</a>
                                        <ul>
                                            @foreach($permissions as $permission)
                                                @if(str_contains($permission->name, 'Users'))
                                                    <li>
                                                        <label>
                                                            <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                                                            {{$permission->name}}
                                                        </label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li><a href="#">Roles</a>
                                        <ul>
                                            @foreach($permissions as $permission)
                                                @if(str_contains($permission->name, 'Roles'))
                                                    <li>
                                                        <label>
                                                            <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                                                            {{$permission->name}}
                                                        </label>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <button class="btn btn-main-primary pd-x-20" type="submit">Add</button>
                            <a class="btn btn-secondary" href="{{ route('roles.index') }}">Back</a>
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
    <!-- Internal Treeview js -->
    <script src="{{URL::asset('assets/plugins/treeview/treeview.js')}}"></script>
@endsection
