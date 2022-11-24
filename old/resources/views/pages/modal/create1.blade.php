<div class="modal fade text-left" id="allModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">Add User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>

        <form action="/save" method = "post">
        {{-- <div class="modal-body" id="bodyModal"> --}}
        <div class="modal-body" >
                <div id = "allMsg"></div> 
                @csrf
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="AccountName">Account Name</label>
                        <input type="text" class="form-control" id="AccountName"  placeholder="Account Name" name = "AccountName" value = "" required autofocus>
                    </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectRole" >Select Role</label>
                
                            <select class="role form-control" name="SelectRole" id="SelectRole" value = "">
                                <option value="" selected disabled>Select Role</option>
                                <option value="{{$aes->encrypt(1)}}">Administrator</option>
                                <option value="{{$aes->encrypt(2)}}">Budget Officer</option>
                                <option value="{{$aes->encrypt(3)}}">Canvasser</option>
                                <option value="{{$aes->encrypt(4)}}">Department</option>
                                <option value="{{$aes->encrypt(5)}}">Supply Officer</option>
                                <option value="{{$aes->encrypt(6)}}">Supply Custodian</option>
                                <option value="{{$aes->encrypt(7)}}">Procurement Officer</option>
                                <option value="{{$aes->encrypt(8)}}">Employee</option>
                                <option value="{{$aes->encrypt(9)}}">Supplier</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Email">Email</label>    
                        <input type="text" class="form-control" id="Email"  placeholder="Email" name = "Email" value = "" required>
                    </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="RoleName">Role Name</label>
                        <input type="text" class="form-control" id="RoleName"  placeholder="Role Name" name = "RoleName" value = "">
                    </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Campus">Campus</label>
                        <input type="text" class="form-control" id="Campus"  placeholder="Campus" name = "Campus" value="{{session('campus') }}" required>
                    </fieldset>
                    </div> 
                </div>
                
        </div>
  
        <div class="modal-footer" id = "footModal">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
            </button>
            <button type="submit" class="btn btn-primary ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Accept</span>
            </button>
        </div>
  
    </form>
        </div>
    </div>
  </div>