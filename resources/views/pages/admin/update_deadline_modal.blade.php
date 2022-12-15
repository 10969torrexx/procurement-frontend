<div class="modal fade text-left" id="UpdateDeadlineModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">UPDATE DEADLINE</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
  
        
        <form action="/ppmp_deadline" method = "post">
        {{-- <div class="modal-body" id="bodyModalCreate"> --}}
        <div class="modal-body" >
            
                <div id = "allMsg"></div> 
                @csrf
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
                <div class="row">
                  <div class="col-md-12">
                      <fieldset class="form-group">
                          <label for="selectyear" >Select Year</label>
              
                          <select class="update_year form-control" id="update_year" required>
                              <option value="" selected disabled>Select Year</option>
                              <option value="{{$aes->encrypt(2023)}}">2023</option>
                              <option value="{{$aes->encrypt(2024)}}">2024</option>
                              <option value="{{$aes->encrypt(2025)}}">2025</option>
                              <option value="{{$aes->encrypt(2026)}}">2026</option>
                              <option value="{{$aes->encrypt(2027)}}">2027</option>
                              <option value="{{$aes->encrypt(2028)}}">2028</option>
                              <option value="{{$aes->encrypt(2029)}}">2029</option>
                              <option value="{{$aes->encrypt(2030)}}">2030</option>
                              <option value="{{$aes->encrypt(2031)}}">2031</option>
                              <option value="{{$aes->encrypt(2032)}}">2032</option>
                              <option value="{{$aes->encrypt(2033)}}">2033</option>
                              <option value="{{$aes->encrypt(2034)}}">2034</option>
                              <option value="{{$aes->encrypt(2035)}}">2035</option>
                              <option value="{{$aes->encrypt(2036)}}">2036</option>
                              <option value="{{$aes->encrypt(2037)}}">2037</option>
                              <option value="{{$aes->encrypt(2038)}}">2038</option>
                              <option value="{{$aes->encrypt(2039)}}">2039</option>
                              <option value="{{$aes->encrypt(2040)}}">2040</option>
                          </select>
                          
                      </fieldset>
                  </div>
              </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <fieldset class="form-group">
                        <label for="StartMonth">Start Date</label>
                        <input type="date" class="update_start_date form-control" id="update_start_date" value ="" >
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <fieldset class="form-group">
                        <label for="EndMonth">End Date</label>
                        <input type="date" class="update_end_date form-control" id="update_end_date" value="" >
                    </fieldset>
                  </div> 
                </div>
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
            <span class="d-none d-sm-block">Update Deadline</span>
            </button>
        </div>
    </form>
        </div>
    </div>
  </div>
  