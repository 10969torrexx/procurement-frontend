<?php

    use App\Http\Controllers\AESCipher;
    $global = new AESCipher();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Employee')

{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/animate/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}">
@endsection

{{-- page styles --}}
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}">
@endsection
{{-- page-styles --}}

@section('content')

<section id="basic-tabs-components">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h6><b>Update Employee</b></h6>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#personal" aria-controls="home" role="tab" aria-selected="true">
                            <i class="bx bx-home align-middle"></i>
                            <span class="align-middle">Personal Information</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    @if(!empty($error))
                    <p class="text-danger"> <a href="#" class="btnrefresh" ><i class="bx bx-refresh"></i></a> {{ $error }}</p>
                    @else
                        @if(isset($employee))
                    <div class="tab-pane active" id="personal" aria-labelledby="home-tab" role="tabpanel">
                        <div class="card">
                            <form class="form-horizontal" id='form-update-employee-personal-profile' enctype="multipart/form-data" >
                                <input type="hidden" name="id" id="pp_id" value="{{ $global->encrypt($employee['id']) }}">
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Firstname*</label>
                                            <div class="controls">
                                                <input type="text" name="firstname" class="form-control " data-validation-required-message="This field is required"
                                                value="{{$employee['FirstName']}}"
                                                >
                                                <span class="text-danger firstname_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Middlename</label>
                                            <div class="controls">
                                                <input type="text" name="middlename" class="form-control " data-validation-required-message="This field is required" 
                                                value="{{$employee['MiddleName']}}"
                                                >
                                                <span class="text-danger middlename_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lastname *</label>
                                            <div class="controls">
                                                <input type="text" name="lastname" class="form-control " data-validation-required-message="This field is required"
                                                value="{{$employee['LastName']}}" >
                                                <span class="text-danger lastname_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">   

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ext</label>
                                            <div class="controls">
                                                <input type="text" name="ext" class="form-control" data-validation-required-message="This field is required" placeholder=""
                                                value="{{$employee['Ext']}}" >
                                                <span class="text-danger ext_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gender*</label>
                                            <div class="controls">
                                                <select class='form-control  border-input' name='sex' value="{{ (empty($employee['Sex'])?"": $global->decrypt($employee['Sex'])) }}" >
                                                       <option value="" selected="true" disabled="disabled"></option>
                                                        <option {{ ($employee['Sex']=='Male' ? 'Selected' : '')  }}  value='Male'>Male</option>
                                                        <option {{ ($employee['Sex']=='Female' ? 'Selected' : '')  }} value='Female'>Female</option>
                                                    </select>
                                                <span class="text-danger sex_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <div class="controls">
                                                <select class="form-control" name="department">
                                                    <option value="" selected="true" disabled="disabled"></option>
                                                    @foreach($departments as $department)
                                                    <option value="{{$global->encrypt($department['id'])}}" <?=($department['id'] == $employee['Department']?"Selected":"")?>> {{$department['DepartmentName']}} </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger department_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cellphone</label>
                                            <div class="controls">
                                                <input type="text" name="cellphone" class="form-control"  value="{{ $employee['Cellphone'] }}" maxlength="11" >
                                                <span class="text-danger cellphone_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <hr>
                                <button type="button" class="btn btn-sm btn-primary" id="btn-update-employee-personal-profile">Save Changes</button>
                            </form>
                        </div>
                    </div>
                    @else
                        <p class="text-danger"> <a href="#" class="btnrefresh" ><i class="bx bx-refresh"></i></a> Server Error</p>
                    @endif
                 @endif
                </div>
            </div>
        </div>
    </div>
</section>
@section('vendor-scripts')
<script src="{{asset('js/admin/employee.js')}}"></script>
<script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>

@endsection
@endsection