<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Departments')

{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/animate/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
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
                    <h4 class="card-title">DEPARTMENTS</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        {{-- <a href = "#" class = "btn btn-success round mr-1 mb-1" data-flag = "{{ $aes->encrypt('accounts')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> New Account</a> --}}
                        <a href = "#" class = "btnNewDepartment btn btn-success round mr-1 mb-1" data-toggle = "modal" data-target = "#AddDepartmentModal"><i class="bx bx-plus"></i> New Department</a>
                        

                        {{-- {{ session('token') }} --}}
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <table class="table nowrap zero-configuration" id="account-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Department Name</th>
                                        <th>Description</th>
                                        <th>Department Head</th>
                                        <th>Immediate Supervisor</th>
                                        <th>Campus</th>
                                    </tr>
                                </thead>
                                <tbody id="table-account-tbody">
                                    @if(!empty($error))
                                    <tr><td class="text-center" colspan="4"><span class="text-danger">{{ $error}}</span></td></tr>
                                    @else
                                        <?php $ctr=1; ?>
                                        @foreach($departments['data'] as $data)
                                            <tr id = "{{$ctr}}">
                                                <td>
                                                    <div class="dropdown">
                                                        <span
                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="dropdown-item editbutton" ctr = "<?=$ctr?>" href = "<?=$aes->encrypt($data['id'])?>" >
                                                                <i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                            <a class="dropdown-item hrefdelete" ctr = "<?=$ctr?>" href = "<?=$aes->encrypt($data['id'])?>">
                                                                <i class="bx bx-trash mr-1"></i> delete </a>
                                                        </div>
                                                    </div>   
                                                </td>
                                                <td>{{$data['department_name']}}</td>
                                                <td>{{$data['description']}}</td>
                                                <td>{{$data['department_head']}}</td>
                                                <td>{{$data['immediate_supervisor']}}</td>
                                                <td>{{(new GlobalDeclare)->Campus($data['campus'])}}</td>
                                            </tr>
                                            <?php $ctr = $ctr + 1 ?>
                                        @endforeach
                                        {{-- @foreach($data as $data)
                                    <tr>
                                        <td>{{$data['name']}}</td>
                                        <td>{{$data['email']}}</td>
                                        <td>{{$data['account_type']}}</td>
                                    </tr>
                                    @endforeach --}}
                                   @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('pages.superadmin.department-modal')
@include('pages.superadmin.update-department-modal')
{{-- @include('pages.admin.update') --}}

{{-- ADD MODAL --}}

{{-- END ADD MODAL --}}



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

{{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

<script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>
{{-- employee JS --}}
<script src="{{asset('js/superadmin/department.js?id=231')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection


  
  {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
  <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
  {{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}

  