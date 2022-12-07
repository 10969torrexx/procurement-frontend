<div class="modal fade text-left" id="UpdateAllocateBudgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">UPDATE ALLOCATED BUDGET</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
  
        {{-- @foreach($ppmp_deadline['deadline'] as $deadline) --}}
        <form action="/" method = "post">
        {{-- <div class="modal-body" id="bodyModalCreate"> --}}
        <div class="modal-body" >
            
                <div id = "allMsg"></div> 
                @csrf

                {{-- <div class="row" hidden>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="YearOf">For the Year of</label>
                          <input type="text" class="year form-control" value ="{{ $deadline['year'] }}" disabled>
                      </fieldset>
                    </div>
                  </div>

                <div class="row" hidden>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="StartDate">Start Date</label>
                          <input type="text" class="start_date form-control"value ="{{ $deadline['start_date'] }}" disabled>
                      </fieldset>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <fieldset class="form-group">
                            <label for="EndDate">End Date</label>
                            <input type="text" class="end_date form-control"  value ="{{ $deadline['end_date'] }}" disabled>
                        </fieldset>
                      </div>
                  </div> --}}
                  {{-- <div class="row">
                    @if (!empty(session("globalerror")))
                  <div class="alert alert-danger" role="alert">
                      {{session("globalerror")}}
                  </div>
                  @endif
                  </div>  --}}
               {{-- <div class="row" > --}}
                  
                  <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="selecttype" >Select Procurement Type</label>
                            <select id="update_type" class="update_type form-control"  required>
                                <option value="" selected disabled>Select Type</option>
                                <option value="{{$aes->encrypt("Indicative")}}">Indicative</option>
                                <option value="{{$aes->encrypt("Supplemental")}}">Supplemental</option>
                                <option value="{{$aes->encrypt("PPMP")}}">PPMP</option>
                            </select>
                        </fieldset>
                    </div>
                  </div> 
               <div class="row" >
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectDepartment" >Select Department</label>
                
                            <select class="update_department form-control" id="update_department" required>
                                <option value="" selected disabled>-- Select Department --</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectFundSource" >Select Fund Source</label>
                
                            <select class="update_fund_source form-control" id="update_fund_source" required>
                                <option value="" selected disabled>-- Select Fund Source --</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="UpdateYear" >Select Year</label>
                            <select id="update_year" class="update_year form-control" required>
                                <option value="" selected disabled>-- Select Year --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <fieldset class="form-group">
                        <label for="Budget">Budget</label>
                        <input type="text" class="update_budget form-control" id="update_budget" placeholder="Enter Budget" value ="" >
                    </fieldset>
                  </div>
                </div>
                
                {{-- <div class="row">
                  <div class="col-md-12">
                  <fieldset class="form-group">
                      <label for="MandatoryExpenditures">Mandatory Expenditures</label>
                      <input type="text" class="update_mandatory_expenditures form-control"  placeholder="Enter Mandatory Expenditures" value="" required>
                  </fieldset>
                  </div> 
              </div> --}}

              <div class="row" hidden>
                <div class="col-md-12">
                <fieldset class="form-group">
                    <label for="Id">Id</label>
                    <input type="text" class="updateid form-control"  placeholder="Id" value="" required>
                </fieldset>
                </div> 
            </div>
        </div>
  
        <div class="modal-footer" id = "footModal">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal" >
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
            </button>
            <button type="submit" class="btn btn-primary ml-1 updatebutton">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Update</span>
            </button>
        </div>
    </form>
    {{-- @endforeach --}}
        </div>
    </div>
  </div>
  