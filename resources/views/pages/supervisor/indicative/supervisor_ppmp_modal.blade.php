<style>
    .ppmp td{
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-bottom: 1px solid black;
        border-top: 1px solid black;
    }
    .align{
        text-align: left;
    }
    .container {
    border: 1px solid rgba(0, 0, 0, .11);
    ;
    padding: 10px;
    width: 500px
    }

    .controls-item {
        display: inline-block;
    }

    .btn {
        margin: 1px;
    }
    .button:focus{
        outline: none;
    }

    
</style>
@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    //$global = new GlobalDeclare();
@endphp
<div class="modal fade" id="viewPPMPmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body RESPONSIVE">
                <div class="table-responsive">
                    <table class="text-center ppmp" style=" line-height:22px;font-size:15px">
                        <thead>
                            <tr style=" border-top: 1px solid black;">
                                <td  rowspan="2" style="width: 16%;">CODE</td>
                                <td  rowspan="2" style="width: 16%;">GENERAL DESCRIPTION</td>
                                <td  colspan="2" rowspan="2" style="width: 16%;">QTY/SIZE</td>
                                <td  style="width: 16%;">ESTIMATED</td>
                                <td  rowspan="2" style="width: 16%;">MODE OF PROCUREMENT</td>
                                <td  colspan="12">SCHEDULE/MILESTONE OF ACTIVITIES</td>
                                <td  rowspan="2" style="width: 22%;">STATUS</td>
                                <td  rowspan="2" style="width: 22%;">APPROVE / <br>DISAPPROVE</td>
                                <td  rowspan="2" style="width: 22%;">REMARKS</td>
                            </tr>
                            <tr>
                                <td style="width: 18%;">BUDGET</td>
                                <td style="width: 1%;">JAN</td>
                                <td style="width: 1%;">FEB</td>
                                <td style="width: 1%;">MAR</td>
                                <td style="width: 1%;">APR</td>
                                <td style="width: 1%;">MAY</td>
                                <td style="width: 1%;">JUN</td>
                                <td style="width: 1%;">JULY</td>
                                <td style="width: 1%;">AUG</td>
                                <td style="width: 1%;">SEPT</td>
                                <td style="width: 1%;">OCT</td>
                                <td style="width: 1%;">NOV</td>
                                <td style="width: 1%;">DEC</td>
                            </tr>
                            <tr >
                                <td style=" font-size: 11px" class="text-dark text-bold-600 code"></td>
                                <td style=" font-size: 11px" class="text-dark text-bold-600 project_title align"></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                            </tr>
                        </thead>
                            {{-- @foreach($data as $data1) --}}
                            {{-- @for ($i = 0; $i < count($data[1]); $i++) --}}
                        <tbody class="tbody">
                        </tbody>
                        <tfoot>
                            <tr >
                                <td colspan="4" style="text-align: right; color:black; font-size:12px" class="text-bold-600">SUB-TOTAL:</td>
                                <td class="total1"></td>
                                <td colspan="16"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer" id = "footModal">
            </div>
        </div>
    </div>
  </div>