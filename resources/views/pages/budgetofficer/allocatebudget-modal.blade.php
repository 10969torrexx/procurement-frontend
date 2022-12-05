<div class="modal fade text-left" id="AllocateBudgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">ALLOCATE BUDGET</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
  
        <form action="/" method = "post">
        {{-- <div class="modal-body" id="bodyModalCreate"> --}}

        <div class="modal-body" >
            
                <div id = "allMsg"></div> 
                @csrf
                {{-- @foreach($ppmp_deadline as $deadline) --}}

                {{-- <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="YearOf">For the Year of</label>
                          <input type="text" class="year form-control" value ="{{ $deadline['year'] }}" disabled>
                      </fieldset>
                    </div>
                  </div> --}}

                <div class="row" hidden>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="StartDate">Start Date</label>
                          {{-- <input type="text" class="start_date form-control"value ="{{ date('M j, Y', strtotime($ppmp_deadline->end_date)) }}" disabled> --}}
                      </fieldset>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <fieldset class="form-group">
                            <label for="EndDate">End Date</label>
                            {{-- <input type="text" class="end_date form-control"  value ="{{ date('M j, Y', strtotime($deadline['end_date'])) }}" disabled> --}}
                            <input type="text" class="end_date form-control" placeholder="End_date" value ="" disabled>
                        </fieldset>
                      </div>
                  </div>
                {{-- @endforeach --}}
                {{-- @if (!empty(session("globalerror")))
                <div class="alert alert-danger" role="alert">
                    {{session("globalerror")}}
                </div>
                @endif --}}
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="selecttype" >Select Procurement Type</label>
                            <select id="type" class="type form-control"  required>
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
                
                            <select class="department form-control" id="department" required>
                                <option value="" selected disabled>-- Select Department --</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectFundSource" >Select Fund Source</label>
                            <select class="fund_source form-control" id="fund_source" required>
                                <option value="" selected disabled>-- Select Fund Source --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="Year" >Select Year</label>
                            <select id="year" class="year form-control" id="year" required>
                                <option value="" selected disabled>-- Select Year --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <fieldset class="form-group">
                        <label for="Budget">Budget</label>
                        <input type="number" class="budget form-control" id="budget" placeholder="Enter Budget" value ="" >
                    </fieldset>
                  </div>
                </div>
                {{-- <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="MandatoryExpenditures">Mandatory Expenditures</label>
                          <input type="text" class="mandatory_expenditures form-control" id="mandatory_expenditures" placeholder="Mandatory Expenditures" value ="" >
                      </fieldset>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <fieldset class="form-group">
                          <label for="Amount">Amount</label>
                          <input type="text" class="amount form-control" id="amount" placeholder="Enter Amount" value ="" >
                      </fieldset>
                    </div>
                  </div> --}}
                {{-- <div class="row" >
                    <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                            <label for="MandatoryExpenditures" >Mandatory Expenditures</label>
                            <select class="mandatory_expenditures form-control" id="mandatory_expenditures" data-placeholder="-- Select Mandatory Expenditures --"  multiple >
                            </select>
                    </div>
                </div> --}}
        </div>
  
        <div class="modal-footer" id = "footModal">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal" >
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
            </button>
            <button type="submit" class="btn btn-primary ml-1 btnAllocateBudget">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Allocate Budget</span>
            </button>
        </div>
</form>
        </div>
    </div>
  </div>