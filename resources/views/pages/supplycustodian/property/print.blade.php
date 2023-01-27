@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Print RIO4</title>
    <link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/ico/favicon.ico')}}">
  </head>
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
<body>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h4 class="card-title">PRINT REPORT</h4> --}}
                   
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                       
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <div id="printThis">
                                <div style="text-align: center; font-weight: 900"><H3>PROPERTY ACKNOWLEDGEMENT RECEIPT</H3></div>
                                <table style="border:1px solid black">
                                    @foreach ($par as $par)
                                    <thead>
                                        <tr style="border: 0">
                                            <td colspan="2">Entity Name:</td>
                                            <td colspan="6" style="text-transform: uppercase;">SOUTHERN LEYTE STATE UNIVERSITY - {{ (new GlobalDeclare)->Campus(session('campus')) }} CAMPUS</td>
                                        </tr>
                                        <tr style="border: 0">
                                            <td colspan="2">Fund cluster:</td>
                                            <td colspan="3" class="fund">{{  $par->FundCluster }}</td>
                                            <td colspan="2" class="Par">PAR No.: {{substr($par->DateIssued, 0, 7)."-".str_pad($par->PARNo, 4, "0", STR_PAD_LEFT) }}</td>
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
            
                                    </thead>
                                    <tbody class="PrintBody">
                                                                        
                                                                    
                                        <tr style="vertical-align: top;">
                                            <td style="text-align: center">{{$par->Quantity}}</td>
                                            <td style="text-align: center">{{$par->unit}}</td>
                                            <td><span style="font-weight: 900">{{$par->ItemName}}</span>
                                                <br>&nbsp;&nbsp;&nbsp;&nbsp;{{$par->Description}}
                                                <br><br>&nbsp;&nbsp;&nbsp;&nbsp;PO NO.{{$par->PONumber}}  
                                                <br>&nbsp;&nbsp;&nbsp;&nbsp;Store {{$par->SupplierName}}   
                                            </td>

                                            <td>{{$par->PropertyNumber}}</td>  
                                            <td>{{$par->DateAcquired}}</td>
                                            <td>{{number_format(str_replace(",","",$par->UnitPrice),2,'.',',') }}</td>  
                                            <td>{{number_format(str_replace(",","",$par->UnitPrice)*$par->Quantity,2,'.',',') }}</td>


                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" style="text-align: left;border-bottom : none;">
                                                Received by:
                                            </td>
                                            <td colspan="4" style="text-align: left;border-bottom : none;">
                                                Received from:
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="border-top : none;">
                                                <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black; text-transform: uppercase; margin-top:30px" class="employeeName"><br>{{  $par->name }} </div>
                                                <div style="text-align: center;">Signature Over Printed Name of End User</div>
            
                                                <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black; margin-top:40px" class="positionTitle"><br>{{  $par->EmpPosition }}</div>
                                                <div style="text-align: center;">Position/Office</div>
                                                <div style="text-align: center;text-decoration: underline;"><?=date('F j, Y')?></div>
                                                <div style="text-align: center;">Date</div>    
            
                                            </td>
            
                                            <td colspan="4" style="border-top : none;">
                                                <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black;margin-top:30px" class="IssuedName"><br>{{  $par->Issuedby }}</div>
                                                <div style="text-align: center;">Signature Over Printed Name of Supply and/or<br>Property Custodian</div>
            
                                                <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black;margin-top:27px" class="IBpositionTitle"><br>{{  $par->IsPosition }}</div>
                                                <div style="text-align: center;">Position/Office</div>
                                                <div style="text-align: center;text-decoration: underline;"><?=date('F j, Y')?></div>
                                                <div style="text-align: center; ">Date</div>    
                                            </td>
                                            
                                        </tr>
                                    </tfoot>
                                    @endforeach
                                </table>
                            </div>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>