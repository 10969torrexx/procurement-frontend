<?php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Users')

{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/animate/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
{{-- <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/toastr.css')}}"> --}}
@endsection
{{-- page-styles --}}
@section('page-styles')
{{-- <link rel="stylesheet" type="text/css" href="{{asset('css/plugins/extensions/toastr.css')}}"> --}}
@endsection

@section('content')
{{-- <div class="row">
    <div class="col-12">
        <p>Read full documnetation <a href="https://datatables.net/" target="_blank">here</a></p>
    </div>
</div> --}}
<!-- Zero configuration table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">ADD NEW USER</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                       
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <table class="ui table nowrap zero-configuration compact" id="account-table" >

                            {{-- <table class="table zero-configuration"> --}}

                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Account Type</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <button type="button" class="btn btn-success round mr-1 mb-1" data-toggle="modal" data-target="#exampleModal"><i class="bx bx-plus"></i> New Account</a>
                                      </button>
                                      
                                      <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                        <div class="modal-dialog" role="document">

                                            <div class="modal-content">

                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">ADD USER</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>

                                                <div class="modal-body">

                                                    <form id = "frmAll" method = "post">
                                                        <div id = "allMsg"></div> 
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="AccountName">Account Name</label>
                                                                <input type="text" class="accountname form-control" id="AccountName"  placeholder="Account Name" name = "AccountName" value = "" required autofocus>
                                                            </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <fieldset class="form-group">
                                                                    <label for="SelectRole" >Select Role</label>
                                                        
                                                                    <select class="role form-control" name="SelectRole" id="SelectRole" value = "">
                                                                        <option value="" selected disabled>Select Role</option>
                                                                        <option value="{{$aes->encrypt(1)}}">Administrator</option>
                                                                        <option value="{{$aes->encrypt(2)}}">Budget Officer</option>
                                                                        <option value="{{$aes->encrypt(3)}}">Canvasser</option>
                                                                        <option value="{{$aes->encrypt(4)}}">Department</option>
                                                                        <option value="{{$aes->encrypt(5)}}">Supply Officer</option>
                                                                        <option value="{{$aes->encrypt(6)}}">Supply Custodian</option>
                                                                        <option value="{{$aes->encrypt(7)}}">Procurement Officer</option>
                                                                        <option value="{{$aes->encrypt(8)}}">Employee</option>
                                                                        <option value="{{$aes->encrypt(9)}}">Supplier</option>
                                                                    </select>
                                                                    
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="Email">Email</label>
                                                                <input type="text" class="email form-control" id="Email"  placeholder="Email" name = "Email" value = "" required>
                                                            </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="RoleName">Role Name</label>
                                                                <input type="text" class="rolename form-control" id="RoleName"  placeholder="Role Name" name = "RoleName" value = "">
                                                            </fieldset>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="Campus">Campus</label>
                                                                <input type="text" class="campus form-control" id="Campus"  placeholder="Campus" name = "Campus" value="" required>
                                                            </fieldset>
                                                            </div> 
                                                        </div>
                                                        
                                                </div>
                                                
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary save">Save changes</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                      @foreach($data as $data)
                                    <tr>
                                        <td>{{$data['name']}}</td>
                                        <td>{{$data['email']}}</td>
                                        <td>{{$data['account_type']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Account Type</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</section>

@endsection
@section('scripts')
<script>
    $(function(){
        $("#SelectRole").change(function(){
            var rolename = $("#SelectRole option:selected").text();
            $("#RoleName").val(rolename);
        });
    })

    $(document).ready(function () {
        
        // $(document).on('click', '.save', function (e) {
        //     e.preventDefault();
        //     // console.log("hello");

        //     var data = {
        //         'accountname': $('.accountname').val(),
        //         'role': $('.role').val(),
        //         'email': $('.email').val(),
        //         'rolename': $('.rolename').val(),
        //         'campus': $('.campus').val(),
        //     }
        //     console.log(data);

        // });

         $(document).on('click', '.save', function (e) {
            e.preventDefault();

            $(this).text('Sending..');
 
            var data = {
                'accountname': $('.accountname').val(),
                'role': $('.role').val(),
                'email': $('.email').val(),
                'rolename': $('.rolename').val(),
                'campus': $('.campus').val(),
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/users",
                data: data,
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.status == 400) {
                        $('#save_msgList').html("");
                        $('#save_msgList').addClass('alert alert-danger');
                        $.each(response.errors, function (key, err_value) {
                            $('#save_msgList').append('<li>' + err_value + '</li>');
                        });
                        $('.add_student').text('Save');
                    } else {
                        $('#save_msgList').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#AddStudentModal').find('input').val('');
                        $('.add_student').text('Save');
                        $('#AddStudentModal').modal('hide');
                        fetchstudent();
                    }
                }
            });

        });
    });
</script>
@endsection
{{-- vendor scripts --}}


@section('vendor-scripts')
<script src="{{asset('js/admin/account.js')}}"></script>

<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>

<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
{{-- <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script> --}}

{{-- <script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script> --}}

{{-- users JS --}}
{{-- <script src="{{asset('js/admin/account.js')}}"></script> --}}

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
