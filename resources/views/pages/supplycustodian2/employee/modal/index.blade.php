<div class="modal fade text-left" id="allEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable " role="document" id = "modalSize">
        <div class="modal-content">
        <div class="modal-header" id = "headModal">
            <h5 class="modal-title" id="myModalLabel160">Employee</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i class="bx bx-x"></i>
            </button>
        </div>
        <div class="modal-body" id = "bodyModal">
           
        <!-- <i class="bx bx-loader-circle"></i> Please wait... -->
        <form id="add-employee-form" autocomplete="off">
            <div class="row">
                    <div class="col-md-4">
                        <fieldset class="form-group">
                        <label for="basicInput">Firstname *</label>
                        <input type="text" name="firstname" class="form-control" >
                        <span class="text-danger firstname_error"></span>
                        </fieldset>
                    </div>
                    <div class="col-md-4">
                        <fieldset class="form-group">
                        <label for="basicInput">Middlename</label>
                        <input type="text" name="middlename" class="form-control" >
                        <span class="text-danger middlename_error"></span>
                        </fieldset>
                    </div>
                    <div class="col-md-4">
                        <fieldset class="form-group">
                        <label for="basicInput">Lastname *</label>
                        <input type="text" class="form-control" name="lastname">
                        <span class="text-danger lastname_error"></span>
                        </fieldset>
                    </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                    <fieldset class="form-group">
                    <label for="basicInput">Ext</label>
                    <input type="text" name="ext" class="form-control" placeholder="eg. Jr" >
                    <span class="text-danger ext_error"></span>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset class="form-group">
                    <label for="basicInput">Gender *</label>
                        <select class="form-control" name="sex">
                            <option value="" selected="true" disabled="disabled"></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        <select>
                        <span class="text-danger sex_error"></span>
                    </fieldset>
                </div>

                <div class="col-md-4">
                    <fieldset class="form-group">
                    <label for="basicInput">Position *</label>
                       <input type="text" class="form-control" name="Position">
                       <span class="text-danger position_error"></span>
                    </fieldset>
                </div>

            </div>
             
            <div class="row">
            
                <div class="col-md-7">
                    <fieldset class="form-group">
                    <label for="basicInput">Department *</label>
                    <select class="form-control" name="department">
                        <option value="" selected="true" disabled="disabled"></option>
                        @foreach($listDepartments as $department)
                        <option value="{{ App\Http\Controllers\AESCipher::encrypt($department['id']) }}"> {{$department['DepartmentName']}} </option>
                        @endforeach
                    </select>
                    <span class="text-danger department_error"></span>
                    </fieldset>
                </div>
                <div class="col-md-5">
                    <fieldset class="form-group">
                    <label for="basicInput">Cellphone *</label>
                    <input type="text" class="form-control" name="cellphone" placeholder="eg. 09123456789" maxlength="11">
                    <span class="text-danger cellphone_error"></span>
                    </fieldset>
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer" id = "footModal">
            <button type="button" class="btn btn-light-secondary btn-sm" data-dismiss="modal">
            <i class="bx bx-x d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Close</span>
            </button>
            <button type="button" class="btn btn-primary btn-sm ml-1" id = "btnemployeeSubmit">
            <i class="bx bx-save"></i> Save
            </button>
        </div>
        </div>
    </div>
</div>