<div class="modal fade text-left" id="UpdateDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" id = "modalSize">
        
      <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">Update Department</h5>
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
                        <input type="text" class="update_department_name form-control" id="update_department_name" placeholder="Department Name" name = "update_department_name" value = "" required autofocus>
                    </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Description">Description</label>
                        <input type="text" class="update_department_description form-control" id="update_department_description"  placeholder="Description" name = "update_department_description" value = "" required >
                    </fieldset>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="DepartmentHead">Department Head</label>
                        <input type="text" class="update_department_head form-control" placeholder="Department Head" name = "update_department_head" value = "" required >
                    </fieldset>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="DepartmentHead" >Department Head</label>
                            <select class="update_department_head form-control" id="update_department_head" required>
                                <option value="" selected disabled>-- Select Campus First --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label for="Supervisor" >Immediate Supervisor</label>
                            <select  id="update_immediate_supervisor" class="update_immediate_supervisor form-control" required>
                                <option value="" selected disabled>-- Select Campus First --</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Supervisor">Immediate Supervisor</label>
                        <input type="text" id="update_immediate_supervisor" class="update_immediate_supervisor form-control" placeholder="Immediate Supervisor" value = "" required >
                    </fieldset>
                    </div>
                </div> --}}
                <div class="row" hidden>
                    <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="Id">Id</label>
                        <input type="text" class="update_id form-control"  placeholder="Id" value="" required>
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
