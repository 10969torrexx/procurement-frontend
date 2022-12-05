<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();

?>
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Supplier')

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
                        <div class = "row">
                            <div class = "col-lg-12 col-xs-12">
                                <a href = "#" class = "btn btn-success  round mr-1 mb-1" data-flag = "{{ $aes->encrypt('supplier')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> Add supplier</a>
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
                                        <option>{{$Error}}</option>
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
                                                            <a class="dropdown-item" href = "#" data-flag = "<?=$aes->encrypt('supplier')?>" data-button = "<?=$aes->encrypt('edit')?>" data-id = "<?=$aes->encrypt($supplier->id)?>" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                            <a class="dropdown-item hrefdelete" ctr = "<?=$ctr?>" button = "<?=$aes->encrypt('delete')?>" flag = "<?=$aes->encrypt('supplier')?>" href = "<?=$aes->encrypt($supplier->id)?>">
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
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>

@endsection