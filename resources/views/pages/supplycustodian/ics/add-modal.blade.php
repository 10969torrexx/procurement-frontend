<div class="modal fade" id="addicsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <input type="text" name="IssuedBy" id="IssuedBy" class="form-control addIssuedBy" value="" hidden>
            <input type="text" name="PAR" id="PAR" class="form-control addPAR" value="" hidden>
            <input type="text" name="addpropertytype" id="addpropertytype" class="form-control addpropertytype" value="" hidden>
            <input type="text" name="addPONumber" id="addPONumber" class="form-control addPONumber" value="" hidden>
            <input type="text" name="DateIssued" id="DateIssued" class="form-control addDateIssued" value="" hidden>
            <input type="text" name="DateAcquired" id="DateAcquired" class="form-control addDateAcquired" value="" hidden>
            <input type="text" name="DateReceived" id="DateReceived" class="form-control addDateReceived" value="" hidden>
            <input type="text" name="addSupplier" id="addSupplier" class="form-control addSupplier" value="" hidden>
            <div class="row ">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="PARNo">PAR #</label>
                        <p name="PARNo" class="addPARNo"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="PONo">PO #</label>
                        <p name="PONo" class="addPONo"></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="addType">Type: </label>
                        <p class="addType" name="addType"></p>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="addEmployeeName">EMPLOYEE NAME *</label>
                        <select name="addEmployeeName" class="form-control addEmployeeName" id="addEmployeeName">
                            <option id="selectedName" value=""></option>
                            <option disabled>---------------------</option>
                            @foreach($users as $users)
                            <option value="{{ $users->id }}">{{ $users->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="FundCluster">Fund Cluster *</label>
                        <select name="FundCluster" class="form-control addFundCluster" id="FundCluster" required>
                            <option id="selectedFund" value = ""></option>
                            <option value="RAF">Regular Agency Fund</option>
                            <option value = "IGF" >Internally Generated Funds</option>
                            <option value="BRF" >Business Related Funds</option>
                        </select>
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-12">
                    <div class="form-group">
                        <label for="ItemName">Item Name *</label>
                        <input type="text" name="ItemName" id="ItemName" class="form-control addItemName" value="">
                    </div >
                </div>
            </div>
        
            <div class="row ">
                <div class="col-12">
                    <div class="form-group">
                        <label for="Description">Description *</label>
                        <textarea name="Description" id="Description" class="form-control addDescription" rows="5"></textarea>
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-xs-4 col-lg-3 col-sm-3">
                    <div class="form-group">
                        <label for="Quantity">Quantity *</label>
                        <input type="number" name="Quantity" id="Quantity" class="form-control addQuantity" value="">
                    </div>
                </div>
        
                <div class="col-xs-4 col-lg-3 col-sm-3">
                    <div class="form-group">
                        <label for="Unit">Unit *</label>
                        <input type="text" name="Unit" id="Unit" class="form-control addUnit" value="">
                    </div>
                </div>
        
                <div class="col-xs-4 col-lg-3 col-sm-3">
                    <div class="form-group">
                        <label for="UnitPrice">Unit Price *</label>
                        <input type="number" name="UnitPrice" id="UnitPrice" class="form-control addUnitPrice" value="">
                    </div>
                </div>
                
                <div class="col-xs-4 col-lg-3 col-sm-3">
                    <div class="form-group">
                        <label for="Estimatedusefullife">Estimated useful life *</label>
                        <input type="text" name="Estimatedusefullife" id="Estimatedusefullife" class="form-control addEstimatedusefullife" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary addPar" value="">Save</button>
        </div>
      </div>
    </div>
</div>