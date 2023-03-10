<?php
  use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;

    $aes = new AESCipher();
    $global = new GlobalDeclare();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Purchase Request')

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



                <div class="card-header" >
                    <h4 class="card-title">SELECT ITEMS FOR PURCHASE REQUEST</h4>
                    <div class="table-responsive">
                        {{-- <h4 class="card-title col-12" style=" text-align: center">Project Title: {{ $details[0]->project_title }} || Fund Source: {{ $details[0]->fund_source }}</h4> --}}
                        <table class="table">
                            <tbody>
                                <tr><td></td><td></td></tr>
                                <tr>
                                    <td>Project Title:  {{$details[0]->project_title }}</td>
                                    <td>Fund Source:    {{$fund_source[0]->fund_source }}</td>
                                    {{-- <th>Mode of Procurement</th> --}}
                                    {{-- <th>Fund Source</th> --}}
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                        </div>
                </div>
                <div class="row" hidden>
                    <div class="col-md-2">
                    <fieldset class="form-group">
                        <input type="text" class="fund_source_id form-control"  placeholder="" value="<?=$aes->encrypt($details[0]->fund_source)?>">
                    </fieldset>
                    </div> 
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        
                        @if($itemsCount != 0)
                            <a href = "#" class = "PR_button btn btn-success round mr-1 mb-1"><i class="bx bx-cart"></i> ADD TO PR</a>
                        @endif

                        <p class="card-text"></p>
                        <div class="table-responsive">

                            <table class="table nowrap zero-configuration" id="account-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <div>Select All <input class="select-all" type="checkbox" value=""></div>
                                        </th>
                                        <th>Item Name</th>
                                        <th>Item Description</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Estimated Price</th>
                                        {{-- <th>Mode of Procurement</th> --}}
                                        {{-- <th>Fund Source</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="table-account-tbody">
                                    @if(!empty($error))
                                    <tr><td class="text-center" colspan="4"><span class="text-danger">{{ $error}}</span></td></tr>
                                    @else
                                        <?php $ctr=1; ?>
                                        @foreach($items as $data)
                                            <tr id = "{{$ctr}}">
                                                <td style="text-align:center;">
                                                    <input class="form-check-input" type="checkbox" value="<?=$aes->encrypt($data->id)?>" required>
                                                </td>
                                                <td>{{ $data->item_name }}</td>
                                                <td>{{$data->item_description}}</td>
                                                <td>{{$data->quantity}}</td>
                                                <td>Php {{number_format($data->unit_price,2,'.',',')}}</td>
                                                <td>Php {{number_format($data->estimated_price,2,'.',',')}}</td>
                                                {{-- <td>{{$data['mode_of_procurement']}}</td> --}}
                                                {{-- <td>{{$data['fund_source']}}</td> --}}
                                            </tr>
                                            <?php $ctr = $ctr + 1 ?>
                                        @endforeach
                                   @endif
                                </tbody>
                            </table>
                            <div class="row" hidden>
                                <div class="col-md-2">
                                <fieldset class="form-group">
                                    <input type="text" class="itemCount form-control"  placeholder="{{$ctr-1}}" value="{{$ctr-1}}">
                                </fieldset>
                                </div> 
                            </div>
                            <div class="row" hidden>
                                <div class="col-md-2">
                                <fieldset class="form-group">
                                    <input type="text" class="project_code form-control"  placeholder="<?=($project_code)?>" value="<?=($project_code)?>">
                                </fieldset>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">DRAFT PURCHASE REQUEST</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">

                        @if($itemsForPRCount != 0)
                            <a href = "#" class = "btn btn-success round mr-1 mb-1" data-toggle = "modal" data-target = "#PreviewPRModal"><i class="bx bx-check"></i> PREVIEW PR</a>
                        @endif

                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <table class="table nowrap zero-configuration" id="account-table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Item Name</th>
                                        <th>Item Description</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Estimated Price</th>
                                    </tr>
                                </thead>
                                <tbody id="table-account-tbody">
                                    @if(!empty($error))
                                    <tr><td class="text-center" colspan="4"><span class="text-danger">{{ $error}}</span></td></tr>
                                    @else
                                        <?php $ctr=1; ?>
                                        @foreach($itemsForPR as $data)
                                            <tr id = "{{$ctr}}">
                                                <td>
                                                    <div class="dropdown">
                                                        <span
                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="dropdown-item editbutton" ctr = "<?=$ctr?>" href = "<?=$aes->encrypt($data->id)?>" >
                                                                <i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                            <a class="dropdown-item removebutton" ctr = "<?=$ctr?>" href = "<?=$aes->encrypt($data->id)?>" >
                                                                <i class="bx bx-trash mr-1"></i> remove </a>
                                                        </div>
                                                    </div>   
                                                </td>
                                                <td>{{ $data->item_name }}</td>
                                                <td>{{$data->item_description}}</td>
                                                <td>{{$data->quantity}}</td>
                                                <td>Php {{number_format($data->unit_price,2,'.',',')}}</td>
                                                <td>Php {{number_format($data->estimated_price,2,'.',',')}}</td>
                                                {{-- <td>{{$data['mode_of_procurement']}}</td> --}}
                                                {{-- <td>{{$data['fund_source']}}</td> --}}
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
</section>
@include('pages.department.preview-PR-modal')
{{-- @include('pages.superadmin.update-allocatebudget-modal') --}}
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

<script src="{{asset('js/department/purchase_request.js')}}"></script>

@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection


  
  {{-- <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script> --}}
  <script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
  {{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}

  