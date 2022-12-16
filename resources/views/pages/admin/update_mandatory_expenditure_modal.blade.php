<div class="modal fade text-left" id="UpdateMandatoryExpenditureModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">UPDATE MANDATORY EXPENDITURE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
  
        <form action="/" method="POST">
        {{-- <div class="modal-body" id="bodyModalCreate"> --}}

        <div class="modal-body" >   
                <div id = "allMsg"></div> 
                @csrf
               
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="MandatoryExpenditure">Mandatory Expenditure</label>
                          <input type="text" id="update_mandatory_expenditure" class="update_mandatory_expenditure form-control" placeholder="Enter Mandatory Expenditure" value ="" required>
                      </fieldset>
                    </div>
                  </div>
                <div class="row" hidden>
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Id">Id</label>
                        <input type="text" id="update_id" class="update_id form-control"  placeholder="Id" value="" required>
                    </fieldset>
                    </div> 
                </div>
        </div>
  
        <div class="modal-footer" id = "footModal">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal" >
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
            </button>
            <button type="submit" class="btn btn-primary ml-1 btnUpdateMandatoryExpenditure">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Update</span>
            </button>
        </div>
</form>
        </div>
    </div>
  </div>