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
                                    <a href = "#" class = "btn btn-primary  round mr-1 mb-1 par_add" data-flag = "{{ $aes->encrypt('property')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allicsModal"><i class="bx bx-plus"></i> Assign property</a>
                                </div>
                                
                            </div>
                            <div class="table-responsive">
                                <table class="table nowrap zero-configuration table-sm">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>ICS #</th>
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
                                            <option>{{$Error}}</option>}
                                        @else
                                        
                                        <?php $ctr=1; ?>
                                        @foreach ($propertys as $property)
                                                @if ($property->Quantity > 0)
                                                    <?php $par = substr($property->DateIssued, 0, 7)."-".str_pad($property->PARNo, 4, "0", STR_PAD_LEFT) ?>
                                                    <tr id="{{$ctr}}">
                                                        <td style="width: 25px">
                                                            @if ($property->finalize == 0)
                                                                <div class="dropdown d-inline-flex {{$ctr}}">
                                                                    <span
                                                                        class="{{$ctr}} bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer"
                                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                                                                    <div class="dropdown-menu dropdown-menu-left">
                                                                        <a class="dropdown-item additempar" href = "#" {{-- data-flag = "<?=$aes->encrypt('property')?>"--}} data-button = "{{ $par }}" data-id = "<?=$aes->encrypt($property->id)?>"  data-toggle = "modal" data-target = "#addicsModal"><i class="bx bx-plus mr-1"></i> add item</a>
                                                                        <a class="dropdown-item editpar" href = "#" data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('edit')?>" data-id = "<?=$aes->encrypt($property->id."-".$property->id)?>" data-toggle = "modal" data-target = "#editicsModal"><i class="bx bx-edit-alt mr-1"></i> edit</a>
                                                                        <a class="dropdown-item deletepar" ctr = "<?=$ctr?>" data-id = "<?=$aes->encrypt($property->id)?>">
                                                                            <i class="bx bx-trash mr-1"></i> delete
                                                                        </a> 
                                                                        <a class="dropdown-item finalize text-danger" button = "{{-- <?=$aes->encrypt('finalize')?> --}}" flag = "{{-- <?=$aes->encrypt('property')?> --}}" data-id = "<?=$aes->encrypt($property->id)?>">
                                                                            <i class="bx bx-check mr-1 text-danger"></i> Finalize
                                                                        </a>
                                                                    </div> 
                                                                </div>    
                                                                
                                                                @else
                                                                <a href = "#" class="transferto" {{-- data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('transfer')?>" --}} data-id = "<?=$aes->encrypt($property->id)?>" data-toggle = "modal" data-target = "#transferModal" title = "Transfer of item"><i class="bx bx-transfer-alt mr-1"></i></a>
                                                                <a href = "#" class="finaldeleteics" {{-- data-flag = "<?=$aes->encrypt('property')?>" data-button = "<?=$aes->encrypt('dispose')?>" --}} data-id = "<?=$aes->encrypt($property->id)?>" data-toggle = "modal" data-target = "#deleteModalics" title = "Dispose of item"><i class="bx bx-trash mr-1 text-danger"></i></a>
                                                                <a href = "#" class="print" data-id = "<?=$aes->encrypt($property->id)?>" data-toggle = "modal" data-target = "#printModalics"><i class="bx bx-printer text-success"></i></a>

                                                                
                                                            @endif 
                                                        </td>
                                                        {{-- <td>{{$par}}</td> --}}
                                                        <td>{{$property->PARNo}}</td>
                                                        <td>{{$property->name}}</td>
                                                        <td>{{$property->FundCluster}}</td>  
                                                        <td>{{$property->PONumber}}</td>  
                                                        <td>{{$property->DateAcquired}}</td> 
                                                        <td>{{$property->SupplierName}}</td> 
                                                        <td>{{$property->ItemName}}</td>
                                                        <td>{{$property->Quantity . " " .$property->unit}}</td>
                                                        <td>{{number_format(str_replace(",","",$property->UnitPrice),2,'.',',') }}</td>
                                                        <td>{{number_format(str_replace(",","",$property->UnitPrice)*$property->Quantity,2,'.',',') }}</td>
                                                        <td>{{$property->remarks}}</td>
                                                    </tr>
                                                    <?php $ctr = $ctr + 1 ?>
                                                @endif
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
  
@include('pages.supplycustodian.ics.ics-modal')
@include('pages.supplycustodian.ics.edit-modal')
@include('pages.supplycustodian.ics.delete-modal')
@include('pages.supplycustodian.ics.add-modal')
@include('pages.supplycustodian.ics.print-modal')
@include('pages.supplycustodian.ics.transfer-modal')
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

<script src="{{asset('js/supplycustodian/ics.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
{{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
 {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}



