<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();

?>
<style>
    table, th, td {
        border: 1px solid;
        font-size: 11px;
        padding: 3px;
    }
    table{
        width: 100%;
    }
</style>

<style type="text/css">
    .hidden_div{
        display: none;
    }

    @media screen {
      #printSection {
          display: none;
      }
    }

    @media print {
      body * {
        visibility:hidden;
        margin: .5cm .5cm .5cm .3cm;
      }
      @page {
          size: A4;
          orientation: landscape;
      }
      table{
        width: 96%;
      }
      #printSection, #printSection * {
        visibility:visible;
      }

      .hidden_div{
        display: block;
        width: 100%;
      }

      a[href]:after {
        content: none !important;
      }

      #printSection {
        position:absolute;
        width: 100%;
        left:0;
        top:0;
      }

    
    }


</style>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Property Custodian')

@section('content')
<!-- Scroll - horizontal and vertical table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                     <h4 class="card-title"><a href = "#" onclick="history.back()"><i class="bx bx-chevrons-left"></i></a> PRINT REPORT</h4>
                   
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                       
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <div id="printThis">
                                <div style="text-align: center;"><H4>INVENTORY OF ITEMS PER EMPLOYEE</H4></div>
                                <table style="border:1px solid black;">
                                    <tr style="border: 0">
                                        <td colspan="2" style="font-size: 15px">Entity Name:</td>
                                        <td colspan="6" style="font-size: 15px">SOUTHERN LEYTE STATE UNIVERSITY - SOGOD CAMPUS</td>
                                    </tr>
                                    <tr style="border: 0">
                                        <td colspan="2" style="font-size: 15px">Employee Name:</td>
                                        <td colspan="6" style="font-size: 15px">{{$subs[0]->EmployeeName}}</td>
                                    </tr>
                                    <tr style="border: 0">
                                        <td colspan="2" style="font-size: 15px">Date Printed:</td>
                                        <td colspan="6" style="font-size: 15px">{{date('F m, Y')}}</td>
                                    </tr>
                                </table>
                                <table class="" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th>PAR #</th>
                                            <th>Type</th>
                                            <th>Fund Cluster</th>
                                            <th>Item</th>
                                            <th style="width: 80px; text-align: center">QTY / Unit</th>
                                            <th style="width: 100px; text-align: center">Unit Price</th>
                                            <th style="width: 100px; text-align: center">Total</th>
                                            <th style="width: 80px; text-align: center">Remarks</th>
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
                                                    <td>{{$sub->propertytype}}</td>  
                                                    <td>{{$sub->FundCluster}}</td>  
                                                    <td>{{$sub->ItemName}}<br><?=wordwrap($sub->Description, 50, "<br>\n")?>
                                                        <br><br>PO#: {{$sub->PONumber}}<br>Supplier: {{$sub->SupplierName}}<br>Date Acquired: {{$sub->DateAcquired}}</td>
                                                    <td style="text-align: center">{{$sub->Quantity . " " .$sub->Unit}}</td>
                                                    <td style="text-align: right">{{number_format(str_replace(",","",$sub->UnitPrice),2,'.',',') }}</td>
                                                    <td style="text-align: right">{{number_format(str_replace(",","",$sub->UnitPrice)*$sub->Quantity,2,'.',',') }}</td>
                                                    <td><?=wordwrap($sub->remarks, 40, "<br>\n")?></td>
                                                </tr>
                                                <?php $ctr = $ctr + 1 ?>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div><br>
                        <a href = "#" onclick="history.back()"><i class="bx bx-chevrons-left"></i> </a> <button type="button" id="btnPrintList" class="btn btn-success">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</section>
<!--/ Scroll - horizontal and vertical table -->
@endsection


{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/ics.js')}}"></script>
@endsection