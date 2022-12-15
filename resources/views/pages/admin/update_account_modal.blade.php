<div class="modal fade text-left" id="UpdateUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">Update User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>

        
        <form action="/save" method = "post">
        {{-- <div class="modal-body" id="bodyModalCreate"> --}}
        <div class="modal-body" >
            
                <div id = "allMsg"></div> 
                @csrf
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="AccountName">Account Name</label>
                        <input type="text" id="updatename" class="updatename form-control"   placeholder="Account Name" name = "accountname" value = "" required autofocus>
                    </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectRole" >Select Role</label>
                
                            <select class="updateselectrole form-control" id="updateselectrole" required>
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
                                <option value="{{$aes->encrypt(10)}}">BAC Secretariat</option>
                                <option value="{{$aes->encrypt(11)}}">Immediate Supervisor</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectDepartment" >Select Department</label>
                
                            <select id="updatedepartment" class="updatedepartment form-control" required>
                                <option value="" selected disabled>-- Select Department --</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Email">Email</label>    
                        <input type="text" id="updateemail" class="updateemail form-control"  name = "email" placeholder="Email" value = "" required>
                    </fieldset>
                    </div>
                </div> --}}

                <div class="row" hidden >
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
        </div>
    </div>
  </div>
{{-- END- --}}
{{-- <script src="{{asset('js/admin/account.js')}}"></script> --}}

  {{-- @include('panels.sweetalert-script') --}}
