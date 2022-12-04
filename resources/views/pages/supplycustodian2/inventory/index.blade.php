<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();

?>
@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Inventory')

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
                <hr>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <form method="POST" action = "/inventory/search" name="frmfundcluster">
                            @csrf
                            <div class="row">
                                <div class="col-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Please select employee</label>
                                        <select class="form-control" name="employee" required>
                                            <option value="{{$aes->encrypt(0)}}"></option>
                                            @foreach ($employees as $employee)
                                                <option value="{{$aes->encrypt($employee->id)}}" {{$employee->id==$oldemployee?"Selected":""}}>{{$employee->FirstName." ".$employee->LastName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control" name="Type">
                                            <option></option>
                                            <option {{$oldType=="ICS"?"Selected":""}}>ICS</option>
                                            <option {{$oldType=="PAR"?"Selected":""}}>PAR</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Fund Cluster</label>
                                        <select class="form-control" name="fundcluster">
                                            <option value = "0"></option>
                                            <option value="RAF" {{$oldCluster=="RAF"?"Selected":""}}>Regular Agency Fund</option>
                                            <option value = "IGF" {{$oldCluster=="IGF"?"Selected":""}}>Internally Generated Funds</option>
                                            <option value="BRF" {{$oldCluster=="BRF"?"Selected":""}}>Business Related Funds</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success" type="submit">Search</button> 
                            @if(empty($error))
                            <a href = "/inventory/print/{{str_replace("/","*",$aes->encrypt($oldemployee))}}/{{(empty($oldType)?"No":$oldType)}}/{{(empty($oldCluster)?"No":$oldCluster)}}" class = "btn btn-danger">Print</a>
                            @endif
                        </form>
                        @if(!empty($error))
                            <div class="mt-2 alert alert-danger">{{$error}}</div>
                        @else
                            <p class="card-text"></p>
   
                            <div class="table-responsive">
                                <table class="table nowrap zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>PAR #</th>
                                            <th>Name</th>
                                            <th>Type</th>
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

                                            @foreach ($subs as $sub)
                                                @if ($sub->Quantity > 0)
                                                <?php $par = substr($sub->DateIssued, 0, 7)."-".str_pad($sub->PARNo, 4, "0", STR_PAD_LEFT) ?>
                                                <tr id="{{$ctr}}">
                                                    <td>{{$par}}</td>
                                                    <td>{{$sub->EmployeeName}}</td>
                                                    <td>{{$sub->propertytype}}</td>  
                                                    <td>{{$sub->FundCluster}}</td>  
                                                    <td>{{$sub->PONumber}}</td>  
                                                    <td>{{$sub->DateAcquired}}</td> 
                                                    <td>{{$sub->SupplierName}}</td> 
                                                    <td>{{$sub->ItemName}}</td>
                                                    <td>{{$sub->Quantity . " " .$sub->Unit}}</td>
                                                    <td>{{number_format(str_replace(",","",$sub->UnitPrice),2,'.',',') }}</td>
                                                    <td>{{number_format(str_replace(",","",$sub->UnitPrice)*$sub->Quantity,2,'.',',') }}</td>
                                                    <td>{{$sub->remarks}}</td>
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