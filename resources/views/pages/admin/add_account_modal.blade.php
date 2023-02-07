<div class="modal fade text-left" id="AddUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">Add User</h5>
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
                            <label for="SelectEmployee" >Select Employee</label>
                
                            <select id="employee" class="employee form-control" required>
                                <option value="" selected disabled>-- Select Employee --</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectRole" >Select Role</label>
                
                            <select id="role" class="role form-control" required>
                                <option value="" selected disabled>-- Select Role --</option>
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
                                <option value="{{$aes->encrypt(12)}}">President/HOPE</option>
                                <option value="{{$aes->encrypt(13)}}">BOR Secretariat</option>
                                <option value="{{$aes->encrypt(14)}}">BAC Committee</option>
                            </select>

                            
                        </fieldset>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectDepartment" >Select Department</label>
                
                            <select id="department" class="department form-control" required>
                                <option value="" selected disabled>-- Select Department --</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Id">ID</label>
                        <input type="text" class="employee_id form-control"   placeholder="ID" name = "employee_id" value = "" required autofocus>
                    </fieldset>
                    </div>
                </div> --}}
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Email">Email</label>    
                        <input type="text" class="email form-control"  name = "email" placeholder="Email" value = "" required>
                    </fieldset>
                    </div>
                </div> --}}
                {{-- <div class="row" hidden>
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="RoleName">Role Name</label>
                        <input type="text" class="rolename form-control" id="rolename" placeholder="Role Name" value = "" required>
                    </fieldset>
                    </div>
                </div> --}}
                
                {{-- <div class="row" hidden>
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Campus">Campus</label>
                        <input type="text" class="echocampus form-control"  placeholder="Campus" value="" required>
                    </fieldset>
                    </div> 
                </div> --}}
                
        </div>
  
        <div class="modal-footer" id = "footModal">
            <button type="button" class="btn btn-light-secondary" data-dismiss="modal" >
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
            </button>
            <button type="submit" class="btn btn-primary ml-1 submitbutton">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Save</span>
            </button>
        </div>
    </form>
        </div>
    </div>
  </div>

 
{{-- END- --}}
{{-- <script src="{{asset('js/admin/account.js')}}"></script> --}}

  {{-- @include('panels.sweetalert-script') --}}
