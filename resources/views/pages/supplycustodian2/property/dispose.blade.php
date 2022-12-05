<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
?>


    <div id="allMsg"></div>
    <div class="row">
            <div class="col-md-4">
                <label for="EmployeeName">Owner:</label>
            </div>
            <div class="col-md-8">
                <?=$old->EmployeeNameSTR?>
            </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label for="EmployeeName">Item Name:</label>
        </div>
        <div class="col-md-8">
            <?=$old->ItemName?>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <label for="Description">Description</label>
        </div>
        <div class="col-md-8">
            {{isset($old->Description)?$old->Description:""}}
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label for="Quantity">Quantity</label>
        </div>
        <div class="col-md-8">
            {{$old->Quantity}} {{$old->Unit}} @ {{number_format(str_replace(",","",$old->UnitPrice),2,'.',',')}}
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label for="PONumber">PO Number</label>
        </div>
        <div class="col-md-8">
            {{isset($old->PONumber)?$old->PONumber:""}}
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <label for="PONumber">Supplier</label>
        </div>
        <div class="col-md-8">
            {{isset($old->SupplierNameSTR)?$old->SupplierNameSTR:""}}
        </div>
    </div>

<hr>
<h5>Dispose Item(s)</h5>
<form id="frmAll" autocomplete="off">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="Quantity">Quantity to dispose*</label>
                <input type="number" class="form-control" name="Quantity" id="Quantity" value="{{$old->Quantity}}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="Remarks">Remarks *</label>
                <textarea class="form-control" name="Remarks" id="Remarks" required></textarea>
            </div>
        </div>
    </div>

</form>
