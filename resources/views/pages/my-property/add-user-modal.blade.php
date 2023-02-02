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

<div class="modal fade" id="AddUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-bottom" role="document">
      <div class="modal-content">
        <div class="modal-body">
            
            <div class="form-group">
                <label for="Username">Name: </label>
                <input type="text" name="Username" id="Username" class="form-control Username" value="">
            </div>

            <div class="form-group">
                <label for="Userquantity">Quantity: </label>
                <input type="number" name="Userquantity" id="Userquantity" class="form-control Userquantity" value="">
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary CloseUser" data-dismiss="modal">Close</button>
          <button type="button" value="" id="AddUser" class="btn btn-success AddUser">Add</button>
        </div>
      </div>
    </div>
</div>