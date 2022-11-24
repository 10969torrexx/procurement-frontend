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
</style>
<div class="modal fade" id="viewSupplementalmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="text-center ppmp" style="width: 100%; line-height:22px;font-size:15px">
                        <thead>
                            <tr style=" border-top: 1px solid black;">
                                <td  rowspan="2" style="width: 16%;">CODE</td>
                                <td  rowspan="2" style="width: 16%;">GENERAL DESCRIPTION</td>
                                <td  colspan="2" rowspan="2" style="width: 16%;">QTY/SIZE</td>
                                <td  style="width: 16%;">ESTIMATED BUDGET</td>
                                <td  rowspan="2" style="width: 16%;">MODE OF PROCUREMENT</td>
                                <td  colspan="12"style="width: 16%;">SCHEDULE/MILESTONE OF ACTIVITIES</td>
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
                                <td colspan="13"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <table class="text-center mt-1 ppmp" style="width: 100%; line-height:22px;font-size:15px">
                        <thead>
                            <tr>
                                <td  colspan="18" style="width: 16%;background-color:dimgrey;color:white">Supplemental PPMP</td>
                            </tr>
                            <tr style=" border-top: 1px solid black;">
                                <td  rowspan="2" style="width: 16%;">CODE</td>
                                <td  rowspan="2" style="width: 16%;">GENERAL DESCRIPTION</td>
                                <td  colspan="2" rowspan="2" style="width: 16%;">QTY/SIZE</td>
                                <td  style="width: 16%;">ESTIMATED BUDGET</td>
                                <td  rowspan="2" style="width: 16%;">MODE OF PROCUREMENT</td>
                                <td  colspan="12"style="width: 16%;">SCHEDULE/MILESTONE OF ACTIVITIES</td>
                            </tr>
                            <tr >
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
                            </tr>
                        </thead>
                            {{-- @foreach($data as $data1) --}}
                            {{-- @for ($i = 0; $i < count($data[1]); $i++) --}}
                        <tbody class="tbody2">
                        </tbody>
                        <tfoot>
                            <tr >
                                <td colspan="4" style="text-align: right; color:black; font-size:12px" class="text-bold-600">SUB-TOTAL:</td>
                                <td class="total2"></td>
                                <td colspan="13"></td>
                            </tr>
                            <tr >
                                <td colspan="4" style="text-align: right; color:black; font-size:12px" class="text-bold-600">OVER ALL TOTAL:</td>
                                <td class="overall"></td>
                                <td colspan="13"></td>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- <div class="container ">
                        <div id="pagination-wrapper"></div>
                    </div> --}}
                </div>
            </div>
            <div class="modal-footer" id = "footModal">
                {{-- <button type="button" class="btn btn-success form-control col-sm-5"><i class="fa-sharp fa-solid fa-file-export"></i>Generate</button> --}}
                
                    {{-- {{ $respose->links() }} --}}
            </div>
        </div>
    </div>
  </div>