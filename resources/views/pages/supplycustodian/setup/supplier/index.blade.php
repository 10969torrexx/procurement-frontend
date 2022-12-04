@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp


@extends('layouts.contentLayoutMaster')
{{-- page Title --}}
@section('title','Property Custodian')
{{-- vendor css --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/charts/apexcharts.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/swiper.min.css')}}">

<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard-ecommerce.css')}}">
@endsection

@section('content')
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">
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
                        <div class = "row">
                            <div class = "col-lg-12 col-xs-12">
                                <a href = "#" class = "btn btn-success  round mr-1 mb-1 addsupplier" data-flag = "{{ $aes->encrypt('supplier')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> Add supplier</a>
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table nowrap zero-configuration">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Name</th>
                                        <th>Contact Number</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody id="property-table">
                                    @if (!empty($Error)){
                                        <option>{{$Error}}</option>}
                                    @else
                                    
                                    <?php $ctr=1; ?>
                                    @foreach ($suppliers as $supplier)
                                    <?php
                                    
                                        
                                     ?>
                                           <tr id="{{$ctr}}">
                                                <td>
                                                    <div class="dropdown">
                                                        <span
                                                            class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                        <div class="dropdown-menu dropdown-menu-left">
                                                            <a class="dropdown-item supplieredit" href = "#" data-flag = "<?=$aes->encrypt('supplier')?>" data-button = "<?=$aes->encrypt('edit')?>" data-id = "<?=$aes->encrypt($supplier->id)?>" data-toggle = "modal" data-target = "#editSupplier"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                            <a class="dropdown-item supplierdelete" ctr = "<?=$ctr?>" button = "<?=$aes->encrypt('delete')?>" flag = "<?=$aes->encrypt('supplier')?>" data-id  = "<?=$aes->encrypt($supplier->id)?>">
                                                                <i class="bx bx-trash mr-1"></i> delete
                                                            </a>
                                                        </div>
                                                    </div>       
                                                </td>
                                                
                                                <td>{{$supplier->SupplierName }}</td>
                                                <td>{{$supplier->ContactNo }}</td>
                                                <td>{{$supplier->Address }}</td>            
                                            </tr>
                                            <?php $ctr = $ctr + 1 ?>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
  
@include('pages.supplycustodian.setup.supplier.add-supplier-modal')
@include('pages.supplycustodian.setup.supplier.edit-supplier-modal')
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
{{-- <script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script> --}}
{{-- <script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script> --}}


<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
{{-- <script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script> --}}

<script src="{{asset('js/supplycustodian/supplier.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
{{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
 {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}



