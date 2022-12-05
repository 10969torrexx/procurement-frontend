<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();

?>
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Property Custodian')

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
                                    <a href = "#" class = "btn btn-primary  round mr-1 mb-1" data-flag = "{{ $aes->encrypt('property')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> Assign property</a>
                                </div>
                                
                            </div>
                            <div class="table-responsive">
                                <table class="table nowrap zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>PAR #</th>
                                            <th>Name</th>
                                            <th>Fund Cluster</th>
                                            <th>PO #</th>
                                            <th>Date Acquired</th>
                                            <th>Store Name</th>
                                            <th>Item</th>
                                            <th>QTY / Unit</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="property-table">
                                        @if (!empty($Error)){
                                            <option>{{$Error}}</option>
                                        @else
                                        
                                        <?php $ctr=1; ?>
                                        @foreach ($propertys as $property)
                                            @foreach ($propertysubs as $sub)
                                                @if ($property->id == $sub->ItemID && $sub->Quantity > 0)
                                                <?php $par = substr($property->DateIssued, 0, 7)."-".str_pad($property->PARNo, 4, "0", STR_PAD_LEFT) ?>
                                                <tr id="{{$ctr}}">
                                                    <td style="width: 25px">
                                                        @if ($property->finalize == 0)
                                                        <div class="dropdown d-inline-flex {{$ctr}}">
                                                            <span
                                                                class="{{$ctr}} bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                            <div class="dropdown-menu dropdown-menu-left">
                                                                <a class="dropdown-item" href = "#" data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('additem')?>" data-id = "<?=$aes->encrypt($property->id)?>" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus mr-1"></i> add item</a>
                                                                <a class="dropdown-item" href = "#" data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('edit')?>" data-id = "<?=$aes->encrypt($property->id."-".$sub->id)?>" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                                <a class="dropdown-item hrefdelete" ctr = "<?=$ctr?>" button = "<?=$aes->encrypt('delete')?>" flag = "<?=$aes->encrypt('property')?>" href = "<?=$aes->encrypt($property->id."-".$sub->id)?>">
                                                                    <i class="bx bx-trash mr-1"></i> delete
                                                                </a> 
                                                                <a class="dropdown-item hrefdelete text-danger" button = "<?=$aes->encrypt('finalize')?>" flag = "<?=$aes->encrypt('property')?>" href = "<?=$aes->encrypt($property->id)?>">
                                                                    <i class="bx bx-check mr-1 text-danger"></i> Finalize
                                                                </a>
                                                            </div> 
                                                        </div>    
                                                        
                                                        @else
                                                        <a href = "#" data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('transfer')?>" data-id = "<?=$aes->encrypt($sub->id)?>" data-toggle = "modal" data-target = "#allModal" title = "Transfer of item"><i class="bx bx-transfer-alt mr-1"></i></a>
                                                        <a href = "#" data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('dispose')?>" data-id = "<?=$aes->encrypt($sub->id)?>" data-toggle = "modal" data-target = "#allModal" title = "Dispose of item"><i class="bx bx-trash mr-1 text-danger"></i></a>
                                                        <a href = "/property/print/{{urlencode($aes->encrypt($property->id))}}/{{$sub->FundCluster}}/{{$sub->EmployeeID}}"><i class="bx bx-printer text-success"></i></a>
                                                        
                                                        @endif 
                                                    </td>
                                                    <td>{{$par}}</td>
                                                    <td>{{$sub->EmployeeName}}</td>
                                                    <td>{{$sub->FundCluster}}</td>  
                                                    <td>{{$property->PONumber}}</td>  
                                                    <td>{{$property->DateAcquired}}</td> 
                                                    <td>{{$property->SupplierNameSTR}}</td> 
                                                    <td>{{$sub->ItemName}}</td>
                                                    <td>{{$sub->Quantity . " " .$sub->Unit}}</td>
                                                    <td>{{number_format(str_replace(",","",$sub->UnitPrice),2,'.',',') }}</td>
                                                    <td>{{number_format(str_replace(",","",$sub->UnitPrice)*$sub->Quantity,2,'.',',') }}</td>
                                                    <td>{{$sub->remarks}}</td>
                                                </tr>
                                                <?php $ctr = $ctr + 1 ?>
                                                @endif
                                            @endforeach
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