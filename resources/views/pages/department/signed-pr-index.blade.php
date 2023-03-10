<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Signed Purchase Request')

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
                    <h4 class="card-title text-center mb-2 mt-2">
                       <strong>UPLOAD SIGNED PURCHASE REQUEST</strong> 
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="container">

                        <form id="upload_signed_pr" method="POST" enctype="multipart/form-data"> @csrf

                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-4">
                                    <fieldset class="form-group">
                                        <label for="PRNo">PR No</label>
                                        <input type="text" id="pr_no"  class="pr_no form-control" placeholder="Enter PR No" name = "pr_no" value = "" required>
                                    </fieldset>
                                </div>
                                <div class="col-sm-4">
                                    <fieldset class="form-group">
                                        <label for="FileName">File Name</label>
                                        <input type="text" id="file_name"  class="file_name form-control" placeholder="Enter File Name" name = "file_name" value = "" required>
                                    </fieldset>
                                </div>
                                {{-- <div class="col-sm-2"></div> --}}

                            </div>

                            <div class="row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-6">
                                    <fieldset class="form-group" >
                                            <label for="">Upload Scanned Signed PR</label>
                                            <input type="file" name="file" class="file form-control" required> 
                                    </fieldset>
                                </div>
                                <div class="col-sm-2 mt-2">
                                    <fieldset class="form-group">
                                        <button type="submit" class="btn btn-success text-white" id="upload_file"><i class="bx bx-upload"></i> Upload</button>
                                    </fieldset>
                                </div>
                            </div>
                        </form>
                        </div>
                    
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">
                       <strong>SIGNED PURCHASE REQUEST</strong> 
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        {{-- <a href = "#" class = "btn btn-success round mr-1 mb-1" data-flag = "{{ $aes->encrypt('accounts')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> New Account</a> --}}
                        {{-- <a href = "#" class = "AllocateBudget btn btn-success round mr-1 mb-1" data-toggle = "modal" data-target = "#AllocateBudgetModal"><i class="bx bx-plus"></i>Allocate Budget</a> --}}
                        {{-- <a href = "allocate_budget1" class = "AllocateBudget1 btn btn-success round mr-1 mb-1"><i class="bx bx-plus"></i>Allocate Budget 1</a> --}}
                        

                        {{-- {{ session('department_id') }} --}}
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <table class="table nowrap zero-configuration" id="account-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>PR No</th>
                                        <th>File Name</th>
                                        <th>Uploaded by</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody id="table-account-tbody">
                                    @if(!empty($error))
                                    <tr><td class="text-center" colspan="4"><span class="text-danger">{{ $error}}</span></td></tr>
                                    @else
                                        <?php $ctr=1; ?>
                                        @foreach($response as $data)
                                            <tr id = "{{$ctr}}">
                                                <td>
                                                    {{-- <form action="{{ route('view_status') }}" method="post">
                                                        @csrf
                                                        <input type="text" id="project_code12" class=" form-control d-none" name="id" value="<?=$aes->encrypt($data->id)?>">
                                                        <button type="submit" class="btn btn-outline-secondary view">view status</button>
                                                    </form> --}}
                                                    <div class="dropdown">
                                                        <span
                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="dropdown-item view_SignedPR_button" ctr = "<?=$ctr?>" href = "{{  $aes->encrypt($data->id) }}" >
                                                                <i class="bx bx-show-alt mr-1 "></i>View Signed PR</a>
                                                            <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('download_signed_pr', ['id' => $aes->encrypt($data->id)]) }}" >
                                                                <i class="bx bx-download mr-1"></i>Download Signed PR</a>
                                                            <a class="dropdown-item edit_SignedPR_button" ctr = "<?=$ctr?>" href = "{{ $aes->encrypt($data->id) }}">
                                                                <i class="bx bx-edit mr-1"></i>Edit Signed PR</a>
                                                            <a class="dropdown-item delete_SignedPR_button" ctr = "<?=$ctr?>" href = "{{ $aes->encrypt($data->id) }}">
                                                                <i class="bx bx-trash mr-1"></i>Delete Signed PR</a>
                                                                {{-- <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('viewPR', ['id' => $aes->encrypt($data->id)]) }}">
                                                                <i class="fa fa-eye mr-2"></i>View PR</a> --}}
                                                            {{-- <a href = "{{ route('department-addItem', ['id' => $aes->encrypt($data['id']) => $aes->encrypt($ProjectTitleResponse[$i]['allocated_budget']) ]) }}" class="dropdown-item">
                                                                <i class = "fa fa-plus mr-2"></i>Add Item</a> --}}
                                                        </div>
                                                    </div>   
                                                </td>
                                                <td>{{$data->pr_no }}</td>
                                                <td>{{$data->file_name}}</td>
                                                <td>{{$data->name}}</td>
                                                <td>{{ date('M. j, Y', strtotime($data->created_at))}}</td>
                                            </tr>
                                            <?php $ctr = $ctr + 1 ?>
                                        @endforeach
                                   @endif
                                </tbody>
                            </table>
                            {{-- <div id="add_to_me">
                                <script>
                                    function createPR() {
                                        document.getElementById("add_to_me").innerHTML +=
                                        "<h3>This is the text which has been inserted by JS</h3>";
                                    }
                                </script> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('pages.department.edit_signedPR_modal')
@include('pages.department.signed-pr-modal')
</section>
{{-- @include('pages.department.view-pr') --}}

@if(Session::has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            html: "{{ Session::get('success')}}",
            })
    </script>
@endif
@if(Session::has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: "{{ Session::get('error')}}",
            })
    </script>
@endif

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

<script>
    $(document).on('click', '.view_SignedPR_button', function(e) {
  e.preventDefault();
  $('#PreviewSignedPR').modal('show');
  $('#content').html('');
  // appending content
  $.ajax({
     headers: {
        'X-CSRF-TOKEN': $("meta[name='csrf-token']").attr('content')
     },
     url: "/PR/signed_pr/view_signed_pr",
     method: 'post',
     data: {
        'id' : $(this).attr('href')
     }, success: function(response) {
        response['data'].forEach(element => {
              $('#content').append(`<iframe src="{{asset("storage/PMIS/signed_purchase_request/`+ element.file_name +`")}}" style="width:100% !important;" height="750" frameborder="0"></iframe>`);
        });
     }
  });
});
</script>
{{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>

<script src="{{asset('vendors/js/extensions/toastr.min.js')}}"></script>
{{-- employee JS --}}

<script src="{{asset('js/department/purchase_request.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection


  
  {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
  <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
  {{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}

  