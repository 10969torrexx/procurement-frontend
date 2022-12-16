<div class="modal fade text-left" id="AddDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">Add Department</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>

        
        <form action="/" method = "post">
        {{-- <div class="modal-body" id="bodyModalCreate"> --}}
        <div class="modal-body" >
            
                <div id = "allMsg"></div> 
                @csrf
                
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="DepartmentName">Department Name</label>
                        <input type="text" id="department_name"  class="department_name form-control" placeholder="Department Name" name = "department_name" value = "" required autofocus>
                    </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Description">Description</label>
                        <input type="text" id="department_description" class="department_description form-control" placeholder="Description" name = "department_description" value = "" required >
                    </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="DepartmentHead" >Department Head</label>
                            <select  id="department_head" class="department_head form-control" required>
                                <option value="" selected disabled>-- Select Campus First --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="Supervisor" >Immediate Supervisor</label>
                            <select  id="immediate_supervisor" class="immediate_supervisor form-control" required>
                                <option value="" selected disabled>-- Select Campus First --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Supervisor">Immediate Supervisor</label>
                        <input type="text" id="immediate_supervisor" class="immediate_supervisor form-control" placeholder="Immediate Supervisor" value = "" required >
                    </fieldset>
                    </div>
                </div> --}}
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="DepartmentHead">Department Head</label>
                        <input type="text" class="department_head form-control" placeholder="Department Head" name = "department_head" value = "" required >
                    </fieldset>
                    </div>
                </div> --}}

                {{-- <div class="row" hidden >
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="SelectDepartment" >Select Department</label>
                
                            <select class="department form-control" required>
                                <option value="" selected disabled>-- Select Department --</option>
                                <option value="{{$aes->encrypt(1)}}">UISA</option>
                                <option value="{{$aes->encrypt(2)}}">CCSIT</option>
                                <option value="{{$aes->encrypt(3)}}">COT</option>
                                <option value="{{$aes->encrypt(4)}}">COE</option>
                                <option value="{{$aes->encrypt(5)}}">CHRTM</option>
                            </select>
                            
                        </fieldset>
                    </div>
                </div> --}}
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="AccountName">Account Name</label>
                        <input type="text" class="accountname form-control"   placeholder="Account Name" name = "accountname" value = "" required autofocus>
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
