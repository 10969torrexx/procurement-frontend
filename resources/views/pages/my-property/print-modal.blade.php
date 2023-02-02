@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    // $global = new GlobalDeclare();
@endphp
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

<div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">PRINT REPORT</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <div id="printThis">
                    <div style="text-align: center; font-weight: 900"><H3>PROPERTY ACKNOWLEDGEMENT RECEIPT</H3></div>
                    <table style="border:1px solid black">
                        <thead>
                            <tr style="border: 0">
                                <td colspan="2">Entity Name:</td>
                                <td colspan="6" style="text-transform: uppercase;">SOUTHERN LEYTE STATE UNIVERSITY - {{ (new GlobalDeclare)->Campus(session('campus')) }} CAMPUS</td>
                            </tr>
                            <tr style="border: 0">
                                <td colspan="2">Fund cluster:</td>
                                <td colspan="3" class="fund">{{-- {{$fund}} --}}</td>
                                <td colspan="2" class="Par">PAR No.: {{-- {{substr($property->DateIssued, 0, 7)."-".str_pad($property->PARNo, 4, "0", STR_PAD_LEFT) }} --}}</td>
                                
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
                                    <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black; text-transform: uppercase; margin-top:30px" class="employeeName"><br> </div>
                                    <div style="text-align: center;">Signature Over Printed Name of End User</div>

                                    <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black; margin-top:40px" class="positionTitle"><br></div>
                                    <div style="text-align: center;">Position/Office</div>
                                    <div style="text-align: center;text-decoration: underline;"><?=date('F j, Y')?></div>
                                    <div style="text-align: center;">Date</div>    

                                </td>

                                <td colspan="4" style="border-top : none;">
                                    <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black;margin-top:30px" class="IssuedName"><br></div>
                                    <div style="text-align: center;">Signature Over Printed Name of Supply and/or<br>Property Custodian</div>

                                    <div style="text-align: center; font-size: 20px; font-weight: 900;border-bottom: 1px solid black;margin-top:27px" class="IBpositionTitle"><br>AO-V</div>
                                    <div style="text-align: center;">Position/Office</div>
                                    <div style="text-align: center;text-decoration: underline;"><?=date('F j, Y')?></div>
                                    <div style="text-align: center; ">Date</div>    
                                </td>
                                
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div><br>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary closePar" data-dismiss="modal">Close</button>
          <button type="button" value="" id="btnPrintList" class="btn btn-success btnPrintList">Print</button>
        </div>
      </div>
    </div>
</div>