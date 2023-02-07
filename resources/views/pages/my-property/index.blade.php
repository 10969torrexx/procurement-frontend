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
                            <div class="table-responsive">

                                {{-- @php
                                    $type = "";
                                @endphp
                                @foreach ($propertys as $prop)
                                    @php
                                        $type = $prop->propertytype;
                                    @endphp
                                @endforeach --}}

                                <form action="{{ route('search-property') }}" method="post">
                                    @csrf
                                    <div class="{{-- col-md-12 text-right  --}} mb-1 row" >
                                            {{-- <input type="text" name="" id="" class="col-sm-2 " placeholder="Search">
                                            <button type="button"  class="btn btn-success col-md-1 mt-1 ppmpDone" >Done</button> --}}
                                            <div class="col-sm-4 text-left" {{-- style="background: black" --}}> 
                                                @if($check != 1 )
                                                    <a href="/property/{{ $type }}" ><button type="button"  class="btn btn-outline-primary col-md-3 " >Show All</button></a>
                                                @endif
                                            </div>
                                            <div class="col-sm-4"></div>
                                            <div class="input-group col-sm-4">
                                                <input type="text" class="form-control Search" name="Search" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                                <input type="hidden" class="form-control " name="Type" value="{{ $type }}">
                                                <div class="input-group-append">
                                                <button class="btn btn-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                                </div>
                                            </div>
                                    </div>
                                </form>
                                <table class="table nowrap zero-configuration table-sm">
                                    <thead>
                                        {{-- <tr style="border: none">
                                            <td colspan="12" >
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <th style="width:80px">Action</th>
                                            @if($type == "PAR")
                                                <th>PAR #</th>
                                            @else
                                                <th>ICS #</th>
                                            @endif
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
                                    <tbody id="property-table" class="ParBody">
                                        @if (!empty($Error)){
                                            <option>{{$Error}}</option>}
                                        @else
                                        
                                        <?php $ctr=1; ?>
                                        @if (count($propertys)!= 0)
                                            @foreach ($propertys as $property)
                                                @if ($property->Quantity > 0)
                                                <?php $par = substr($property->DateIssued, 0, 7)."-".str_pad($property->PARNo, 4, "0", STR_PAD_LEFT) ?>
                                                <tr id="{{$ctr}}">
                                                    <td style="width: 25px">
                                                        {{-- @if ($property->finalize > 0) --}}
                                                            <div {{-- class="ml-1" --}} style="text-align:center">
                                                                <a href = "#" class="print" data-id = "<?=$aes->encrypt($property->id)?>" data-toggle = "modal" data-target = "#printModal"><i class="bx bx-printer text-success"></i></a>
                                                                <a href = "#" class="currentUser ml-1" value="{{ $property->Quantity }}" data-id = "<?=$aes->encrypt($property->id)?>" data-toggle = "modal" data-target = "#userModal" title="View Current User"><i class="bx bx-note text-primary"></i></a>
                                                            </div>
                                                        {{-- @endif  --}}
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
                                                    <td>
                                                        <?php $transfer = "";
                                                        if($property->ItemStatus == 1){
                                                            echo $property->remarks.' ( Transfered )';
                                                        }else{
                                                            echo $property->remarks;
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php $ctr = $ctr + 1 ?>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr><td colspan="12" style="text-align: center;" > <span style="margin-top:100px">No Data</span></td></tr>
                                        @endif
                                        @endif
                                    </tbody>
                                </table>
                                {{ $propertys->onEachSide(1)->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
  
{{-- @include('pages.supplycustodian.property.property-modal')
@include('pages.supplycustodian.property.edit-modal')
@include('pages.supplycustodian.property.add-modal') --}}
@include('pages.my-property.print-modal')
@include('pages.my-property.user-modal')
@include('pages.my-property.add-user-modal')
{{-- @include('pages.supplycustodian.property.delete-modal')
@include('pages.supplycustodian.property.transfer-modal') --}}
</section>
<!-- Dashboard Ecommerce ends -->
@endsection

@section('vendor-scripts')
{{-- <script src="{{asset('vendors/js/charts/apexcharts.min.js')}}"></script> --}}
{{-- <script src="{{asset('vendors/js/extensions/swiper.min.js')}}"></script> --}}


{{-- <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>  --}}
{{-- <script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script> --}}

<script src="{{asset('js/myproperty/property.js')}}"></script>
@endsection

@section('page-scripts')
{{-- <script src="{{asset('js/scripts/pages/dashboard-ecommerce.js')}}"></script> --}}
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection
  
<script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('vendors/js/extensions/polyfill.min.js')}}"></script>
{{-- <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> --}}
 {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script> --}}



