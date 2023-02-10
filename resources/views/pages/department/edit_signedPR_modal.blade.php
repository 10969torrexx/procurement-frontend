  <div class="modal fade" id="EditSignedPRModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">EDIT SIGNED PR</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="updateSignedPR" method="POST" enctype="multipart/form-data"> @csrf
                <div class="col-sm-12" hidden>
                    <fieldset class="form-group">
                        <label for="ID">ID</label>
                        <input type="text" id="signedPR_id"  class="signedPR_id form-control" name = "signedPR_id" value = "" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="PRNo">PR No</label>
                        <input type="text" id="update_pr_no"  class="update_pr_no form-control" placeholder="Enter PR No" name = "update_pr_no" value = "" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <fieldset class="form-group">
                        <label for="FileName">File Name</label>
                        <input type="text" id="update_file_name"  class="update_file_name form-control" placeholder="Enter File Name" name = "update_file_name" value = "" required>
                    </fieldset>
                </div>
                <div class="col-sm-12">
                    <fieldset class="form-group" >
                            <label for="">Upload Scanned Signed PR</label>
                            <input type="file" name="update_file" class="update_file form-control" required> 
                    </fieldset>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
        </form>
      </div>
    </div>
  </div>
