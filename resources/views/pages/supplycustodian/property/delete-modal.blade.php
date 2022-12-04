<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">PAR</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="text" name="checkQuantity" id="checkQuantity" class="form-control checkQuantity" value="" hidden>
                <div class="col-md-4">
                    <label>Owner:</label>
                </div>
                <div class="col-md-6">
                    <p class="dataEmployeeName"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Item Name: </label>
                </div>
                <div class="col-md-6">
                    <p class="dataItemName"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Description: </label>
                </div>
                <div class="col-md-6">
                    <p class="dataDescription"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Quantity: </label>
                </div>
                <div class="col-md-6">
                    <p class="dataQuantity"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>PO Number: </label>
                </div>
                <div class="col-md-6">
                    <p class="dataPONo"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Supplier: </label>
                </div>
                <div class="col-md-6">
                    <p class="dataSupplier"></p>
                </div>
            </div>
           <hr> 
           <h5>Dispose Item(s)</h5>
            <div class="row">
                <div class="col-md-12">
                    <label for="disposequantity">QUANTITY TO DISPOSE*</label>
                    <input type="number" class=" form-control disposequantity" name="disposequantity" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="remarks">REMARKS*</label>
                    <textarea name="remarks" id="remarks" class="form-control remarks" rows="5" value="" required></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger deletefinal" value="">Delete</button>
        </div>
      </div>
    </div>
</div>