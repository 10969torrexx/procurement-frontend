<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                <input type="text" name="transfercheckQuantity" id="transfercheckQuantity" class="form-control transfercheckQuantity" value="" hidden>
                <input type="text" name="transferfrom" id="transferfrom" class="form-control transferfrom" value="" hidden>
                <div class="col-md-4">
                    <label>Owner:</label>
                </div>
                <div class="col-md-6">
                    <p class="transferEmployeeName"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Item Name: </label>
                </div>
                <div class="col-md-6">
                    <p class="transferItemName"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Description: </label>
                </div>
                <div class="col-md-6">
                    <p class="transferDescription"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Quantity: </label>
                </div>
                <div class="col-md-6">
                    <p class="transferQuantity"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>PO Number: </label>
                </div>
                <div class="col-md-6">
                    <p class="transferPONo"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label>Supplier: </label>
                </div>
                <div class="col-md-6">
                    <p class="transferSupplier"></p>
                </div>
            </div>
           <hr> 
           <h5>Transfer Item(s)</h5>
           <div class="row">
                <div class="col-md-12">
                    <label for="transfertoEmployeeName">EMPLOYEE NAME *</label>
                    <select name="transfertoEmployeeName" class="form-control transfertoEmployeeName" id="transfertoEmployeeName">
                        <option value="">Choose...</option>
                        <option disabled>---------------------</option>
                        {{-- @foreach($users as $users)
                        <option value="{{ $users->id }}">{{ $users->name }}</option>
                        @endforeach --}}
                    </select>
                </div>
           </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="transfer2quantity">QUANTITY TO Transfer*</label>
                    <input type="number" class=" form-control transfer2quantity" name="transfer2quantity" value="" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="transferremarks">REMARKS*</label>
                    <textarea name="transferremarks" id="transferremarks" class="form-control transferremarks" rows="5" value="" required></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success transferfinal" value="">Transfer</button>
        </div>
      </div>
    </div>
</div>