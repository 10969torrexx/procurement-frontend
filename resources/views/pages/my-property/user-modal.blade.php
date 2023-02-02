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

<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">MASTERLIST</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="userbutton">
                {{-- <a href="#" type="button" class="btn btn-primary " data-toggle = "modal" data-target = "#AddUserModal" value=""><i class="fa-solid fa-plus"></i> &nbsp; Add</a> --}}
            </div>
            <div class="table-responsive mt-1">
                <div id="printThis">
                    {{-- <div style="text-align: center; font-weight: 900"><H3>MASTERLIST</H3></div> --}}
                    <table style="border:1px solid black">
                        <thead style="text-align: center">
                            <tr style="border: 0">
                                <th>Action</th>
                                <th>Item</th>
                                <th>Current User</th>
                                <th>Quantity</th>
                                <th>Date Distributed</th>
                            </tr>

                        </thead>
                        <tbody class="CurrentBody">

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div><br>

        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-secondary closePar" data-dismiss="modal">Close</button>
          <button type="button" value="" id="btnPrintList" class="btn btn-success btnPrintList">Print</button> --}}
        </div>
      </div>
    </div>
</div>