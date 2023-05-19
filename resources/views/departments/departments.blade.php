@extends('layouts.master')
@section('title', 'Departments')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Settings</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ Departments</span>
            </div>
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
            <div class="card">
                @can('Add Departments')
                    <div class="col-sm-6 col-md-3 col-xl-2 mt-3">
                        <a class="modal-effect btn btn-primary btn-block" data-toggle="modal" href="#addNewDepartmentModal">Add Department</a>
                    </div>
                @endcan
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                            <tr>
                                <th class="wd-5p border-bottom-0">#</th>
                                <th class="wd-15p border-bottom-0">Department Name</th>
                                <th class="wd-15p border-bottom-0">Description</th>
                                <th class="wd-15p border-bottom-0">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 0; @endphp
                            @foreach($departments as $department)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$department->name}}</td>
                                <td>{{$department->description}}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @can('Edit Departments')
                                            <div onclick="getDepartmentInfo('{{$department->id}}', '{{$department->name}}', '{{$department->description}}')">
                                                <a class="btn btn-primary btn-sm" data-toggle="modal" href="#updateDepartmentModal"title="Edit"><i class="las la-pen"></i></a>
                                            </div>
                                        @endcan
                                        @can('Delete Departments')
                                            <div onclick="deleteDepartment('{{$department->id}}', '{{$department->name}}')">
                                                <a class="btn btn-danger btn-sm" data-toggle="modal" href="#deleteDepartmentModal" title="Delete"><i class="las la-trash"></i></a>
                                            </div>
                                        @endcan
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

    <!-- Model | Add New Department -->
    <div class="modal fade" id="addNewDepartmentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Add New Department</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{route('departments.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="col-lg mb-3">
                            <label for="departmentName">Department Name</label>
                            <input class="form-control" name="name" type="text" id="departmentName">
                        </div>
                        <div class="col-lg">
                            <label for="departmentDescription">Description</label>
                            <textarea class="form-control" name="description" id="departmentDescription" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" type="submit">Save</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Model | Update Department -->
    <div class="modal fade" id="updateDepartmentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Update Department</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="departments/update" method="post">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="col-lg mb-3">
                            <label for="departmentName">Department Name</label>
                            <input class="form-control" name="name" type="text" id="updateDepartmentName">
                        </div>
                        <div class="col-lg">
                            <label for="departmentDescription">Description</label>
                            <textarea class="form-control" name="description" id="updateDepartmentDescription" rows="3"></textarea>
                        </div>
                        <input type="hidden" id="updateDepartmentId" name="departmentId">
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" type="submit">Update</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Model | Delete Department -->
    <div class="modal fade" id="deleteDepartmentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Delete Department</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="departments/destroy" method="post">
                    @method('DELETE')
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure want to delete this department?</p><br>
                        <input class="form-control" id="deleteDepartmentName" type="text" readonly>
                        <input type="hidden" id="deleteDepartmentId" name="departmentId">
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
                    msg: "{{ session('status') }}",
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
    <script>
        function getDepartmentInfo(departmentId, departmentName, departmentDescription){
            document.querySelector('#updateDepartmentName').value = departmentName;
            document.querySelector('#updateDepartmentDescription').value = departmentDescription;
            document.querySelector('#updateDepartmentId').value = departmentId;
        }
        function deleteDepartment(departmentId, departmentName){
            document.querySelector('#deleteDepartmentName').value = departmentName;
            document.querySelector('#deleteDepartmentId').value = departmentId;
        }
    </script>
@endsection
