<div class="modal fade" id="allicsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">ICS Property</h5>
          <button type="button" class="close ics_closed" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row ">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="EmployeeName">Employee Name *</label>
                        <select name="EmployeeName" class="form-control EmployeeName" id="EmployeeName">
                            <option selected>Choose...</option>
                            @foreach($users as $users)
                            <option value="{{ $users->id }}">{{ $users->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Type">Type *</label>
                        <input type="text" name="Type" id="Type" class="form-control Type" value="ICS" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="FundCluster">Fund Cluster *</label>
                        <select name="FundCluster" class="form-control FundCluster" id="FundCluster" required>
                            <option selected>Choose...</option>
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
                        <input type="text" name="ItemName" id="ItemName" class="form-control ItemName" value="">
                    </div >
                </div>
            </div>
        
            <div class="row ">
                <div class="col-12">
                    <div class="form-group">
                        <label for="Description">Description *</label>
                        <textarea name="Description" id="Description" class="form-control Description" rows="5"></textarea>
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="Quantity">Quantity *</label>
                        <input type="number" name="Quantity" id="Quantity" class="form-control Quantity" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="Unit">Unit *</label>
                        {{-- <input type="text" name="Unit" id="Unit" class="form-control Unit" value=""> --}}
                        <select name="Unit" class="form-control Unit" id="Unit">
                            <option selected>Choose...</option>
                            @foreach($unit as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_of_measurement }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="UnitPrice">Unit Price *</label>
                        <input type="number" name="UnitPrice" id="UnitPrice" class="form-control UnitPrice" value="">
                    </div>
                </div>
            </div>
        
            <div class="row ">
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="Issuedby">Issued by *</label>
                        <select name="Issuedby" class="form-control Issuedby" id="Issuedby">
                            <option selected>Choose...</option>
                            @foreach($issuedby as $issuedby)
                            <option value="{{  $issuedby->id }}">{{ $issuedby->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="ICSNumber">ICS Number *</label>
                        <input type="number" name="ICSNumber" id="ICSNumber" class="form-control ICSNumber" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="DateIssued">Date Issued *</label>
                        <input type="date" name="DateIssued" id="DateIssued" class="form-control DateIssued" value="">
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="DateAcquired">Date Acquired *</label>
                        <input type="date" name="DateAcquired" id="DateAcquired" class="form-control DateAcquired" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="PONumber">PO Number *</label>
                        <input type="text" name="PONumber" id="PONumber" class="form-control PONumber" value="">
                    </div>
                </div>
        
                <div class="col-xs-6 col-lg-4 col-sm-4">
                    <div class="form-group">
                        <label for="DateReceived">Date Received *</label>
                        <input type="date" name="DateReceived" id="DateReceived" class="form-control DateReceived" value="">
                    </div>
                </div>
            </div>
        
            <div class="row ">
                <div class="col-8">
                    <div class="form-group">
                        <label for="Supplier">Supplier *</label>
                        <select name="Supplier" class="form-control Supplier" id="Supplier" required>
                            <option selected>Choose...</option>
                            @foreach($supplier as $supplier)
                            <option value="{{  $supplier->id }}">{{ $supplier->SupplierName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="Estimatedusefullife">Estimated useful life *</label>
                        <input type="text" name="Estimatedusefullife" id="Estimatedusefullife" class="form-control Estimatedusefullife" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary ics_closed" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary parSave">Save</button>
        </div>
      </div>
    </div>
</div>