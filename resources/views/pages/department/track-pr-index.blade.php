<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Track Purchase Request')

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
                    <h4 class="card-title text-center mt-2">
                        <strong>PURCHASE REQUEST</strong>
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
                                        <th>PR No.</th>
                                        <th>Fund Source</th>
                                        <th>Purpose</th>
                                        <th>Printed Name</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody id="table-account-tbody">
                                    @if(!empty($error))
                                    <tr><td class="text-center" colspan="4"><span class="text-danger">{{ $error}}</span></td></tr>
                                    @else
                                        <?php $ctr=1; ?>
                                        @foreach($pr as $data)
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
                                                            <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('view_pr', ['id' => $aes->encrypt($data->id)]) }}" >
                                                                <i class="bx bx-show-alt mr-1"></i>View PR</a>
                                                            <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('view_status', ['id' => $aes->encrypt($data->id)]) }}">
                                                                <i class="bx bx-task mr-1"></i>View Status</a>
                                                            @if($data->status == 0 || $data->status == 1 || $data->status == 3)
                                                            <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('edit_pr', ['id' => $aes->encrypt($data->id) , 'pr_no' => $aes->encrypt($data->pr_no)]) }}" >
                                                                <i class="bx bx-edit-alt mr-1"></i> Edit</a>
                                                            <a class="dropdown-item deletebutton" ctr = "<?=$ctr?>" href = "{{ $aes->encrypt($data->id) }}" rel = "{{ $aes->encrypt($data->pr_no) }}">
                                                                <i class="bx bx-trash mr-1"></i> Delete </a>
                                                            @endif
                                                            
                                                                {{-- <a class="dropdown-item" ctr = "<?=$ctr?>" href = "{{ route('viewPR', ['id' => $aes->encrypt($data->id)]) }}">
                                                                <i class="fa fa-eye mr-2"></i>View PR</a> --}}
                                                            {{-- <a href = "{{ route('department-addItem', ['id' => $aes->encrypt($data['id']) => $aes->encrypt($ProjectTitleResponse[$i]['allocated_budget']) ]) }}" class="dropdown-item">
                                                                <i class = "fa fa-plus mr-2"></i>Add Item</a> --}}
                                                        </div>
                                                    </div>   
                                                </td>
                                                <td>{{$data->pr_no }}</td>
                                                <td>{{$data->fund_source}}</td>
                                                <td>{{$data->purpose}}</td>
                                                <td>{{$data->name}}</td>
                                                @if($data->status == 0)
                                                <td><div class="badge badge-pill badge-light-warning mr-1">Draft</div></td>
                                                @elseif($data->status == 1)
                                                <td style="color:blue;"><div class="badge badge-pill badge-light-primary mr-1">Pending</div></td>
                                                @elseif($data->status == 2)
                                                <td style="color:green;"><div class="badge badge-pill badge-light-success mr-1">Approved</div></td>
                                                @elseif($data->status == 3)
                                                <td ><div class="badge badge-pill badge-light-danger mr-1">Disapproved</div></td>
                                                @endif
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
</section>
{{-- @include('pages.department.preview-PR-modal') --}}
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

  