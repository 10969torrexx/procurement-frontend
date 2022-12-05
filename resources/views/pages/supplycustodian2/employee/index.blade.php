<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();

?>
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Employee')

{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
@endsection
{{-- page-styles --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">
@endsection

@section('content')
<!-- Scroll - horizontal and vertical table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">MASTERLIST</h4>
                   
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        @if(!empty($error))
                        <p class="text-danger"> <a href="#" class="btnrefresh" ><i class="bx bx-refresh"></i></a> {{ $error }}</p>
                        @else
                        <p class="card-text"></p>
                        <form id="frmAllSearch" method="POST" action="/employee/search-department-employment-status">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" name="Department">
                                            <?php
                                                if (!empty($Error)){
                                                    echo '<option>'.$Error.'</option>';
                                                }else{
                                                    echo '<option>All</option>';
                                                    foreach ($listDepartments as $department) {
                                                        echo '<option value = "'.$department['id'].'" '.($department['id'] == $oldDepartment?"Selected":"").'>'.$department['DepartmentName'].'</option>';
                                                    }
                                                }
                                                
                                            ?>
                                        </select>
                                    </div>
                                </div>


                                
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button type = "submit" class = "btn btn-sm btn-success" id="btnSearch">Search</button>
                                        <button type="button" class="btn btn-sm btn-primary" id='add-employee-btn'>Add New</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            
                            <table class="table nowrap zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Gender</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody id="employee-table">
                                    @if (!empty($Error)){
                                        <option>{{$Error}}</option>
                                    @else
                                    
                                    <?php $ctr=1; ?>
                                    @foreach ($listEmployees as $employee)
                                        <tr id="{{$ctr}}">
                                            <td>
                                                <div class="dropdown">
                                                    <span
                                                        class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <a class="dropdown-item" href="{{ route('employee.edit',['f' => $aes->encrypt($employee->id) ])  }}"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                        <a class="dropdown-item btn-employee-delete" href="#" ctr = "<?=$ctr?>" data-id="{{ $aes->encrypt($employee->id) }}"><i class="bx bx-trash mr-1"></i> delete</a>
                                                    </div>
                                                </div>       
                                            </td>
                                            
                                            <td>{{$employee->LastName . ", " . $employee->FirstName. (empty($employee->MiddleName)?"":" ".$employee->MiddleName[0].".") }}</td>
                                            <td>{{ $employee->PositionTitle }}</td>
                                            <td>{{ $employee->Sex }}</td>
                                            
                                            <td>
                                                <?php 
                                                    foreach ($listDepartments as $department) {
                                                        if($employee->Department == $department->id ){
                                                            echo $department->DepartmentName;
                                                        }
                                                    }
                                                ?> 
                                            </td>
                                        </tr>
                                        <?php $ctr = $ctr + 1 ?>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @include('employee.modal.index')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</section>
<!--/ Scroll - horizontal and vertical table -->
@endsection
{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

<script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>
<script src="{{asset('js/admin/employee.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection