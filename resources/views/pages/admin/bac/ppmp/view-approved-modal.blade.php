<style>
    .ppmp td{
        border-left: 1px solid black;
        border-right: 1px solid black;
        border-bottom: 1px solid black;
        border-top: 1px solid black;
    }
    .align{
        text-align:left;
    }

    
</style>
<div class="modal fade" id="viewmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <tr style="font-weight: 900;">
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
                            {{-- <tr >
                                <td style=" font-size: 11px" class="text-dark text-bold-600 code"></td>
                                <td style=" font-size: 11px" class="text-dark text-bold-600 project_title"></td>
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
                            <tr >
                                <td style=" font-size: 11px" class="text-dark text-bold-600 "></td>
                                <td style=" font-size: 12px;font-weight:900" class="text-dark text-bold-600 item"></td>
                                <td  class="quantity " style="width: 40px;"></td>
                                <td  class="unit" ></td>
                                <td  class="budget" ></td>
                                <td  class="mode" ></td>
                                <td  class="jan months" ></td>
                                <td  class="feb months"></td>
                                <td  class="mar months"></td>
                                <td  class="apr months"></td>
                                <td  class="may months" ></td>
                                <td  class="jun months"></td>
                                <td  class="jul months"></td>
                                <td  class="aug months"></td>
                                <td  class="sep months"></td>
                                <td  class="oct months"></td>
                                <td  class="nov months"></td>
                                <td  class="dec months"></td>
                            </tr>
                            <tr >
                                <td style=" font-size: 11px" class="text-dark text-bold-600 "></td>
                                <td style=" font-size: 11px" class="text-dark text-bold-600 description"></td>
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
                            </tr> --}}
                                
                        </tbody>
                        
                        <tfoot>
                            <tr >
                                <td colspan="4" style="text-align: right; color:black; font-size:12px" class="text-bold-600">TOTAL:</td>
                                <td class="total"></td>
                                <td colspan="13"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer" id = "footModal">
                {{-- <button type="button" class="btn btn-success form-control col-sm-5"><i class="fa-sharp fa-solid fa-file-export"></i>Generate</button> --}}
            </div>
        </div>
    </div>
  </div>