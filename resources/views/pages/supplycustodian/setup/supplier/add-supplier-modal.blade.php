<div class="modal fade" id="allModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="SupplierName">Supplier Name *</label>
                        <input type="text" name="SupplierName" id="SupplierName" class="form-control SupplierName" value = "" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="Address">Address</label>
                        <input type="text" name="Address" id="Address" class="form-control Address" value = "">
                    </div>
                </div>
            </div>
            <div class="row">
        
                <div class="col-12">
                    <div class="form-group">
                        <label for="ContactNumber">Contact Number</label>
                        <input type="number" name="ContactNumber" id="ContactNumber" class="form-control ContactNumber" value = "">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="Description">Description</label>
                        <textarea name="Description" id="Description" class="form-control Description" rows="5"></textarea>
                    </div >
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary supplierSave">Save</button>
        </div>
      </div>
    </div>
</div>