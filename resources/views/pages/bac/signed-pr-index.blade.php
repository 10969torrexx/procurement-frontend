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
                    <h4 class="card-title text-center">
                       <strong>SIGNED PURCHASE REQUEST</strong> 
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <table class="table nowrap zero-configuration" id="account-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>PR No</th>
                                        <th>File Name</th>
                                        <th>Department</th>
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
                                                    <div class="dropdown">
                                                        <span
                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="dropdown-item view_SignedPR_button" ctr = "<?=$ctr?>" href = "{{  $aes->encrypt($data->id) }}" >
                                                                <i class="bx bx-show-alt mr-1 "></i>View Signed PR</a>
                                                            <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('download_signed_pr', ['id' => $aes->encrypt($data->id)]) }}" >
                                                                <i class="bx bx-download mr-1"></i>Download Signed PR</a>
                                                        </div>
                                                    </div>   
                                                </td>
                                                <td>{{$data->pr_no }}</td>
                                                <td>{{$data->file_name}}</td>
                                                <td>{{$data->department_name}}</td>
                                                <td>{{$data->name}}</td>
                                                <td>{{ date('M. j, Y', strtotime($data->created_at))}}</td>
                                            </tr>
                                            <?php $ctr = $ctr + 1 ?>
                                        @endforeach
                                   @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

  