
<form id="frmAll" autocomplete="off">
    <div id="allMsg"></div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="SupplierName">Supplier Name *</label>
                <input type="text" name="SupplierName" id="SupplierName" class="form-control" value = "{{ $oldData['OldSupplierName'] }}" required>
            <div class="form-group">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="Address">Address</label>
                <input type="text" name="Address" id="Address" class="form-control" value = "{{ $oldData['OldAddress'] }}">
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            <div class="form-group">
                <label for="ContactNumber">Contact Number</label>
                <input type="number" name="ContactNumber" id="ContactNumber" class="form-control" value = "{{ $oldData['OldContactNo'] }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="Description">Description</label>
                <textarea name="Description" id="Description" class="form-control" rows="5">{{ $oldData['OldDescription'] }}</textarea>
            <div class="form-group">
        </div>
    </div>
</form>
