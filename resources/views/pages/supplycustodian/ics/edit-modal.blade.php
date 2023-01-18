<div class="modal fade" id="editicsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row ">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="EditEmployeeName">Employee Name *</label>
                        <select name="EditEmployeeName" class="form-control EditEmployeeName" id="EditEmployeeName">
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
                        <label for="Type">Type *</label>
                        {{-- <input type="text" name="Type" id="Type" class="form-control sample" value="" disabled> --}}
                        <input type="text" name="Type" id="Type" class="form-control EditType" value="PAR" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="FundCluster">Fund Cluster *</label>
                        <select name="FundCluster" class="form-control EditFundCluster" id="FundCluster" required>
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
                        <input type="text" name="ItemName" id="ItemName" class="form-control EditItemName" value="">
                    </div >
                </div>
            </div>
        
            <div class="row ">
                <div class="col-12">
                    <div class="form-group">
                        <label for="Description">Description *</label>
                        <textarea name="Description" id="Description" class="form-control EditDescription" rows="5"></textarea>
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="Quantity">Quantity *</label>
                        <input type="number" name="Quantity" id="Quantity" class="form-control EditQuantity" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="Unit">Unit *</label>
                        {{-- <input type="text" name="Unit" id="Unit" class="form-control EditUnit" value=""> --}}
                        <select name="EditUnit" class="form-control EditUnit" id="EditUnit">
                            <option id="EditUnitSelected" value=""></option>
                            <option disabled>---------------------</option>
                            @foreach($unit as $units)
                            <option value="{{ $units->id }}">{{ $units->unit_of_measurement }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="UnitPrice">Unit Price *</label>
                        <input type="number" name="UnitPrice" id="UnitPrice" class="form-control EditUnitPrice" value="">
                    </div>
                </div>
            </div>
        
            <div class="row ">
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="Issuedby">Issued by *</label>
                        <select name="Issuedby" class="form-control EditIssuedby" id="Issuedby">
                            <option id="selectedIssuedBy" value=""></option>
                            @foreach($issuedby as $issuedby)
                            <option value="{{  $issuedby->id }}">{{ $issuedby->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="EditICSNo">ICS Number *</label>
                        <input type="text" name="EditICSNo" id="EditICSNo" class="form-control EditICSNo" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="DateIssued">Date Issued *</label>
                        <input type="date" name="DateIssued" id="DateIssued" class="form-control EditDateIssued" value="">
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="DateAcquired">Date Acquired *</label>
                        <input type="date" name="DateAcquired" id="DateAcquired" class="form-control EditDateAcquired" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="PONumber">PO Number *</label>
                        <input type="text" name="PONumber" id="PONumber" class="form-control EditPONumber" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="DateReceived">Date Received *</label>
                        <input type="date" name="DateReceived" id="DateReceived" class="form-control EditDateReceived" value="">
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-8">
                    <div class="form-group">
                        <label for="Supplier">Supplier *</label>
                        <select name="Supplier" class="form-control EditSupplier" id="Supplier" required>
                            <option id="selectedSupplier" value="" ></option>
                            @foreach($supplier as $supplier)
                            <option value="{{  $supplier->id }}">{{ $supplier->SupplierName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="Estimatedusefullife">Estimated useful life *</label>
                        <input type="text" name="Estimatedusefullife" id="Estimatedusefullife" class="form-control EditEstimatedusefullife" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary submitEdit" value="">Save</button>
        </div>
      </div>
    </div>
</div>