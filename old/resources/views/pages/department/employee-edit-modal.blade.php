  <div class="modal fade" id="EmployeeEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Employee</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <fieldset class="form-group">
                        <label for="SelectEmployee" >Select Employee</label>
            
                        <select id="employee" class="employee form-control" required>
                            <option value="" selected disabled>-- Choose --</option>
                        </select>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary saveButton">Save</button>
        </div>
      </div>
    </div>
  </div>