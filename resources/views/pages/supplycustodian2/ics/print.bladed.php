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
                    <h4 class="card-title">PRINT REPORT</h4>
                   
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                       
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <div id="printThis">
                                <div style="text-align: center; font-weight: 900"><H3>PROPERTY ACKNOWLEDGEMENT RECEIPT</H3></div>
                                <table style="border:1px solid black">
                                    <tr style="border: 0">
                                        <td colspan="2">Entity Name:</td>
                                        <td colspan="6">SOUTHERN LEYTE STATE UNIVERSITY - SOGOD CAMPUS</td>
                                    </tr>
                                    <tr style="border: 0">
                                        <td colspan="2">Fund cluster:</td>
                                        <td colspan="3">{{$fund}}</td>
                                        <td colspan="2">PAR No.: {{substr($property->DateIssued, 0, 7)."-".str_pad($property->PARNo, 4, "0", STR_PAD_LEFT) }}</td>
                                    </tr>
                                    </tr>
                                        <tr style="text-align: center">
                                            <td style="width: 10%">Quantity</td>
                                            <td style="width: 10%">Unit</td>
                                            <td style="width: 30%">Description</td>
                                            <td style="width: 12.5%">Property<br>Number</td>
                                            <td style="width: 12.5%">Date<br>Acquired</td>
                                            <td style="width:12.5%">Unit<br>Price</td>
                                            <td style="width:12.5%">Amount</td>
                                        </tr>

                                        @foreach ($subs as $sub)
                                            
                                        
                                        <tr style="vertical-align: top;">
                                            <td style="text-align: center">{{$sub->Quantity}}</td>
                                            <td style="text-align: center">{{$sub->Unit}}</td>
                                            <td><span style="font-weight: 900">{{$sub->ItemName}}</span>
                                                <br>&nbsp;&nbsp;&nbsp;&nbsp;{{$sub->Description}}
                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;PO NO.{{$property->PONumber}}  
                                                <br>&nbsp;&nbsp;&nbsp;&nbsp;Store {{$property->SupplierNameSTR}}   
                                            </td>

                                            <td>{{$property->PropertyNumber}}</td>  
                                            <td>{{$property->DateAcquired}}</td>
                                            <td>{{number_format(str_replace(",","",$sub->UnitPrice),2,'.',',') }}</td>  
                                            <td>{{number_format(str_replace(",","",$sub->UnitPrice)*$sub->Quantity,2,'.',',') }}</td>


                                        </tr>
                                        @endforeach
                                    <tr>
                                        <td colspan="3">

                                            <div style="text-align: left">Received by:</div>
                                            <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black"><br><?=strtoupper($property->EmployeeName)?></div>
                                            <div style="text-align: center;">Signature Over Printed Name of End User</div>

                                            <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black"><br><?=$property->EmpPositionTitle?></div>
                                            <div style="text-align: center;">Position/Office</div>
                                            <div style="text-align: center;text-decoration: underline;"><?=date('F j, Y')?></div>
                                            <div style="text-align: center;">Date</div>    

                                        </td>

                                        <td colspan="4">
                                            <div style="text-align: left">Received from:</div>
                                            <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black"><br>MIGUEL M. BIDON</div>
                                            <div style="text-align: center;">Signature Over Printed Name of Supply and/or<br>Property Custodian</div>

                                            <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black"><br>AO-V</div>
                                            <div style="text-align: center;">Position/Office</div>
                                            <div style="text-align: center;text-decoration: underline;"><?=date('F j, Y')?></div>
                                            <div style="text-align: center; ">Date</div>    
                                        </td>
                                        
                                    </tr>
                                </table>
                            </div>
                        </div><br>
                        <button type="button" id="btnPrintList" class="btn btn-success">Print</button>
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